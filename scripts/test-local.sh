#!/bin/bash
# Local Test Runner — Run before pushing
# Auto-detects changed files and runs appropriate tests
# NOTE: chmod +x this file before use
#
# Usage:
#   ./scripts/test-local.sh                    # Auto-detect changed files
#   ./scripts/test-local.sh backend            # Test backend only
#   ./scripts/test-local.sh frontend           # Test frontend only
#   ./scripts/test-local.sh all                # Test everything
#   ./scripts/test-local.sh --quick            # Compile/lint only (no full tests)

set -e
set -o pipefail

# =============================================================================
# CONFIGURE THESE FOR YOUR PROJECT
# =============================================================================
# Each entry: "label:path:type" where type is java|node|python|php
# Example: "api:services/api:java" "web:frontend:node" "ml:ml-service:python"
# DongHo: Laravel 12 monolith — backend (PHP) + frontend assets (Vite/node) in the same root.
PROJECT_DIRS=(
    "backend:.:php"
    "frontend:.:node"
)

# Patterns to detect which project dir changed (glob matched against file paths)
# Maps to PROJECT_DIRS by index. If empty, all dirs are tested.
DETECT_PATTERNS=(
    "app/*|routes/*|config/*|database/*|tests/*|composer.json|composer.lock|phpunit.xml"
    "resources/*|public/*|package.json|package-lock.json|vite.config.js|tailwind.config.js|postcss.config.js"
)

# Files that count as "docs only" (no tests needed)
DOCS_PATTERNS="documents/*|*.md|*.txt|*.rst|LICENSE|CHANGELOG"
# =============================================================================

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(dirname "$SCRIPT_DIR")"
cd "$ROOT_DIR"

QUICK_MODE=false
PASSED=0
FAILED=0
SKIPPED=0

# Parse arguments
if [[ "$1" == "--quick" ]]; then
    QUICK_MODE=true
    shift
fi

EXPLICIT_TARGET="${1:-}"

echo -e "${BLUE}==========================================================${NC}"
echo -e "${BLUE}  Local Test Runner (pre-push)${NC}"
echo -e "${BLUE}==========================================================${NC}"
echo ""

# Helper: run a test step
run_step() {
    local name="$1"
    local cmd="$2"
    local dir="$3"

    echo -e "${BLUE}> $name${NC}"
    if (cd "$dir" && eval "$cmd") 2>&1 | tail -5; then
        echo -e "${GREEN}  [OK] $name passed${NC}"
        PASSED=$((PASSED + 1))
    else
        echo -e "${RED}  [!!] $name FAILED${NC}"
        FAILED=$((FAILED + 1))
    fi
    echo ""
}

# Run tests for a project dir by type
run_tests_for() {
    local label="$1"
    local dir="$2"
    local type="$3"

    if [ ! -d "$dir" ]; then
        echo -e "${YELLOW}  [SKIP] $label: directory not found ($dir)${NC}"
        SKIPPED=$((SKIPPED + 1))
        return
    fi

    case "$type" in
        java)
            if [ -f "$dir/mvnw" ]; then
                if $QUICK_MODE; then
                    run_step "$label: Compile" "./mvnw compile -q 2>&1" "$dir"
                else
                    run_step "$label: Compile" "./mvnw compile -q 2>&1" "$dir"
                    run_step "$label: Unit Tests" "./mvnw test -q 2>&1" "$dir"
                fi
            elif [ -f "$dir/gradlew" ]; then
                if $QUICK_MODE; then
                    run_step "$label: Compile" "./gradlew compileJava 2>&1" "$dir"
                else
                    run_step "$label: Build" "./gradlew build 2>&1" "$dir"
                fi
            else
                echo -e "${YELLOW}  [SKIP] $label: no mvnw or gradlew found${NC}"
                SKIPPED=$((SKIPPED + 1))
            fi
            ;;
        node)
            if [ -f "$dir/package.json" ]; then
                if $QUICK_MODE; then
                    # Try lint first, fall back to tsc, then vite build (assets-only projects)
                    if grep -q '"lint"' "$dir/package.json"; then
                        run_step "$label: Lint" "npm run lint 2>&1" "$dir"
                    elif grep -q '"tsc"' "$dir/package.json" || [ -f "$dir/tsconfig.json" ]; then
                        run_step "$label: Type Check" "npx tsc --noEmit 2>&1" "$dir"
                    elif grep -q '"build"' "$dir/package.json"; then
                        run_step "$label: Build" "npm run build 2>&1" "$dir"
                    else
                        echo -e "${YELLOW}  [SKIP] $label: no lint/tsc/build script in package.json${NC}"
                        SKIPPED=$((SKIPPED + 1))
                    fi
                else
                    # Try vitest, jest, or generic test script
                    if grep -q '"vitest"' "$dir/package.json"; then
                        run_step "$label: Vitest" "npx vitest run --reporter=verbose 2>&1" "$dir"
                    elif grep -q '"jest"' "$dir/package.json"; then
                        run_step "$label: Jest" "npx jest --verbose 2>&1" "$dir"
                    elif grep -q '"test"' "$dir/package.json"; then
                        run_step "$label: Tests" "npm test 2>&1" "$dir"
                    else
                        echo -e "${YELLOW}  [SKIP] $label: no test runner found${NC}"
                        SKIPPED=$((SKIPPED + 1))
                    fi
                fi
            else
                echo -e "${YELLOW}  [SKIP] $label: no package.json found${NC}"
                SKIPPED=$((SKIPPED + 1))
            fi
            ;;
        python)
            if [ -f "$dir/pyproject.toml" ] || [ -f "$dir/setup.py" ] || [ -f "$dir/requirements.txt" ]; then
                if $QUICK_MODE; then
                    if command -v ruff &>/dev/null; then
                        run_step "$label: Ruff Lint" "ruff check . 2>&1" "$dir"
                    elif command -v flake8 &>/dev/null; then
                        run_step "$label: Flake8" "flake8 . 2>&1" "$dir"
                    fi
                else
                    if command -v pytest &>/dev/null; then
                        run_step "$label: Pytest" "pytest -v 2>&1" "$dir"
                    elif [ -f "$dir/manage.py" ]; then
                        run_step "$label: Django Tests" "python manage.py test 2>&1" "$dir"
                    else
                        echo -e "${YELLOW}  [SKIP] $label: no test runner found${NC}"
                        SKIPPED=$((SKIPPED + 1))
                    fi
                fi
            else
                echo -e "${YELLOW}  [SKIP] $label: no Python project found${NC}"
                SKIPPED=$((SKIPPED + 1))
            fi
            ;;
        php)
            if [ -f "$dir/composer.json" ]; then
                # Detect a runnable PHP, in order:
                #   1. $PHP_BIN env var (explicit override)
                #   2. `php` in PATH
                #   3. Common XAMPP locations on Windows (C:\xampp\php\php.exe etc.)
                #   4. Laravel Sail (Docker) — last resort
                PHP_RUN=""
                if [ -n "${PHP_BIN:-}" ] && [ -x "$PHP_BIN" ]; then
                    PHP_RUN="$PHP_BIN"
                elif command -v php &>/dev/null; then
                    PHP_RUN="php"
                else
                    for xampp_php in \
                        "/c/xampp/php/php.exe" \
                        "/d/xampp/php/php.exe" \
                        "/e/xampp/php/php.exe" \
                        "/f/xampp/php/php.exe" \
                        "C:/xampp/php/php.exe" \
                        "D:/xampp/php/php.exe"; do
                        if [ -x "$xampp_php" ]; then
                            PHP_RUN="$xampp_php"
                            break
                        fi
                    done
                fi
                if [ -z "$PHP_RUN" ] && [ -x "$dir/vendor/bin/sail" ] && command -v docker &>/dev/null && docker info &>/dev/null; then
                    PHP_RUN="./vendor/bin/sail php"
                fi
                if [ -z "$PHP_RUN" ]; then
                    echo -e "${YELLOW}  [SKIP] $label: no usable PHP (not in PATH, no XAMPP at C:/xampp/php, no Sail/Docker)${NC}"
                    SKIPPED=$((SKIPPED + 1))
                elif $QUICK_MODE; then
                    if [ -x "$dir/vendor/bin/pint" ]; then
                        run_step "$label: Pint (style)" "$PHP_RUN vendor/bin/pint --test 2>&1" "$dir"
                    elif [ -x "$dir/vendor/bin/php-cs-fixer" ]; then
                        run_step "$label: PHP-CS-Fixer (dry)" "$PHP_RUN vendor/bin/php-cs-fixer fix --dry-run --diff 2>&1" "$dir"
                    fi
                    if [ -d "$dir/app" ]; then
                        run_step "$label: PHP syntax (app+routes)" \
                            "find app routes -name '*.php' -print0 | xargs -0 -n1 $PHP_RUN -l > /dev/null 2>&1 && echo 'syntax OK'" "$dir"
                    fi
                else
                    if [ -f "$dir/artisan" ]; then
                        run_step "$label: Artisan Test" "$PHP_RUN artisan test 2>&1" "$dir"
                    elif [ -x "$dir/vendor/bin/phpunit" ]; then
                        run_step "$label: PHPUnit" "$PHP_RUN vendor/bin/phpunit 2>&1" "$dir"
                    else
                        echo -e "${YELLOW}  [SKIP] $label: no artisan/phpunit found (run composer install)${NC}"
                        SKIPPED=$((SKIPPED + 1))
                    fi
                fi
            else
                echo -e "${YELLOW}  [SKIP] $label: no composer.json found${NC}"
                SKIPPED=$((SKIPPED + 1))
            fi
            ;;
        *)
            echo -e "${YELLOW}  [SKIP] $label: unknown type '$type'${NC}"
            SKIPPED=$((SKIPPED + 1))
            ;;
    esac
}

# --- Auto-detect or explicit target ---
if [ -n "$EXPLICIT_TARGET" ] && [ "$EXPLICIT_TARGET" != "all" ]; then
    # Run specific target
    FOUND=false
    for entry in "${PROJECT_DIRS[@]}"; do
        IFS=: read -r label dir type <<< "$entry"
        if [ "$label" = "$EXPLICIT_TARGET" ]; then
            run_tests_for "$label" "$dir" "$type"
            FOUND=true
            break
        fi
    done
    if ! $FOUND; then
        echo -e "${RED}Unknown target: $EXPLICIT_TARGET${NC}"
        echo "Available targets:"
        for entry in "${PROJECT_DIRS[@]}"; do
            IFS=: read -r label dir type <<< "$entry"
            echo "  $label ($type) -> $dir"
        done
        exit 1
    fi
elif [ "$EXPLICIT_TARGET" = "all" ]; then
    # Run all
    for entry in "${PROJECT_DIRS[@]}"; do
        IFS=: read -r label dir type <<< "$entry"
        run_tests_for "$label" "$dir" "$type"
    done
else
    # Auto-detect based on changed files
    CHANGED=$(git diff --cached --name-only 2>/dev/null || git diff HEAD --name-only 2>/dev/null || echo "")

    if [ -z "$CHANGED" ]; then
        echo -e "${YELLOW}No changed files detected. Run with explicit target:${NC}"
        echo "  $0 <target>    # Test specific project"
        echo "  $0 all         # Test everything"
        echo ""
        echo "Available targets:"
        for entry in "${PROJECT_DIRS[@]}"; do
            IFS=: read -r label dir type <<< "$entry"
            echo "  $label ($type) -> $dir"
        done
        exit 0
    fi

    # Check if docs only
    HAS_CODE=false
    while IFS= read -r file; do
        [[ -z "$file" ]] && continue
        IS_DOC=false
        IFS='|' read -ra PATS <<< "$DOCS_PATTERNS"
        for pat in "${PATS[@]}"; do
            # shellcheck disable=SC2254
            case "$file" in $pat) IS_DOC=true; break ;; esac
        done
        if ! $IS_DOC; then
            HAS_CODE=true
            break
        fi
    done <<< "$CHANGED"

    if ! $HAS_CODE; then
        echo -e "${GREEN}[OK] Only documentation changes -- no tests needed${NC}"
        exit 0
    fi

    # Detect which project dirs have changes
    echo -e "${BLUE}Auto-detecting changes...${NC}"
    TESTED_ANY=false

    for i in "${!PROJECT_DIRS[@]}"; do
        IFS=: read -r label dir type <<< "${PROJECT_DIRS[$i]}"
        PATTERN="${DETECT_PATTERNS[$i]:-$dir/*}"

        HAS_CHANGES=false
        while IFS= read -r file; do
            [[ -z "$file" ]] && continue
            # shellcheck disable=SC2254
            case "$file" in $PATTERN) HAS_CHANGES=true; break ;; esac
        done <<< "$CHANGED"

        if $HAS_CHANGES; then
            echo -e "  Changed: ${BLUE}$label${NC} ($dir)"
            run_tests_for "$label" "$dir" "$type"
            TESTED_ANY=true
        fi
    done

    if ! $TESTED_ANY; then
        echo -e "${YELLOW}No project dirs matched changed files.${NC}"
        echo "Configure PROJECT_DIRS and DETECT_PATTERNS in this script."
    fi
fi

# --- Summary ---
echo -e "${BLUE}==========================================================${NC}"
echo -e "  Results: ${GREEN}[OK] $PASSED passed${NC}  ${RED}[!!] $FAILED failed${NC}  ${YELLOW}[--] $SKIPPED skipped${NC}"
echo -e "${BLUE}==========================================================${NC}"

if [ $FAILED -gt 0 ]; then
    echo -e "${RED}FIX FAILURES BEFORE PUSHING!${NC}"
    exit 1
fi

echo -e "${GREEN}All tests passed -- safe to push${NC}"
