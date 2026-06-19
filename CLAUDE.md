# Claude Code Instructions

## Communication Language
**ALWAYS communicate in Vietnamese (Tiếng Việt)**
- All responses and explanations in Vietnamese
- Code comments in English (standard practice)
- Commit messages in English (git convention)
- UI strings / Blade views in Vietnamese (per existing codebase)

## Project Overview
**DongHo** — Website thương mại điện tử bán đồng hồ (đồ án tốt nghiệp DATN).

**Architecture:** Laravel 12 monolith (PHP 8.2+) + Blade/Tailwind/Alpine + Vite + SQLite (default).
- **Backend:** Laravel 12, PHPUnit 11
- **Frontend:** Blade templates + TailwindCSS 3 + Alpine.js 3, bundled bằng Vite 7
- **Auth:** custom (email + OTP) + Google OAuth (Laravel Socialite)
- **Payment:** VNPay + VietQR
- **AI:** Google Gemini (xem `app/Services/GeminiService.php`, log ở model `AiLog`)
- **Domain models:** Product, Order, OrderItem, Brand, Category, Coupon, Review, Comment, News, Wishlist, Banner, OTP, User

## Development Workflow

### Superpowers Methodology (every PR)
1. **Quick Brainstorm** (5-10 min) — `.claude/skills/core/brainstorming-methodology.md`
   - Analyze scope, risks, edge cases
   - Identify dependencies and blockers
2. **Task Breakdown** (5-10 min) — `.claude/skills/core/task-breakdown-guide.md`
   - Split into specific tasks with effort estimates
3. **TDD - Test First** — `.claude/skills/core/tdd-enforcement.md`
   - Write tests BEFORE code: Red -> Green -> Refactor
   - Laravel: dùng PHPUnit feature/unit tests trong `tests/`
4. **Implementation**
   - Follow task breakdown, commit frequently
5. **Code Review** (self-review) — `.claude/skills/core/two-stage-code-review.md`

### Do NOT skip:
- Do not jump straight into code without brainstorming
- Do not write code before tests
- Do not commit without accompanying tests

## Scripts (MUST use scripts, NOT ad-hoc commands)

| Script | Purpose |
|--------|---------|
| `scripts/test-local.sh` | Test locally before push (PHPUnit + Vite build) |
| `scripts/test-local.sh --quick` | Quick lint/compile only (Pint + Vite build) |
| `scripts/check-ci.sh` | Wait for CI after push |
| `scripts/check-ci.sh --status` | Quick CI status check |
| `scripts/pre-commit-check.sh` | Pre-commit compliance |

**Laravel-specific shortcuts (gọi qua composer):**
| Command | Purpose |
|---------|---------|
| `composer dev` | Khởi động đồng thời `artisan serve` + queue + pail logs + vite dev |
| `composer test` | Chạy PHPUnit (`artisan test`) sau khi clear config |
| `php artisan migrate` | Chạy migration |
| `php artisan db:seed` | Chạy seeder |
| `npm run dev` | Vite dev server |
| `npm run build` | Vite production build |

## Git Workflow
- **ALWAYS** create feature branch before changes
- **NEVER** commit directly to main
- **Branch naming:** `feature/{description}` or `fix/{description}`
- Self-test before push: `scripts/test-local.sh`
- Monitor CI after push: `scripts/check-ci.sh`
- *Note:* hiện tại repo CHƯA init git — nhớ `git init` trước khi setup hooks.

## Business Logic Documents
**Location:** `documents/01-business/` — SOURCE OF TRUTH

**Structure:** 3-layer per domain (gợi ý cho dự án DongHo):
```
documents/01-business/
  catalog/        # Product, Brand, Category
    rules.md
    use-cases.md
    api-contract.md
  ordering/       # Order, OrderItem, Coupon
  auth/           # User, OTP, Google OAuth
  payment/        # VNPay, VietQR
  reviews/        # Review, Comment
  ai/             # Gemini integration
```

**Rules:**
- Doc and code MUST be in the same PR
- Do NOT hardcode business rules — đọc từ config / `rules.md`

## Living Documents
| Doc | Update when |
|-----|-----------|
| `README.md` | Đổi tech stack, thêm/bỏ service tích hợp |
| `CLAUDE.md` | Đổi quy trình, convention |
| `documents/01-business/` | Đổi business rule (giá, coupon, payment flow, …) |

## Project Structure
```
DongHo/
├── .claude/                 # Skills, scripts, hooks
├── app/
│   ├── Http/Controllers/    # Controllers (Auth, …)
│   ├── Models/              # Eloquent models (Product, Order, …)
│   ├── Services/            # Gemini, OTP, VNPay, VietQR
│   ├── Mail/, Jobs/, View/, Providers/
├── bootstrap/, config/, database/
├── documents/               # Business + architecture docs (source of truth)
│   ├── 01-business/
│   ├── 02-architecture/
│   ├── 03-planning/
│   └── 04-quality/
├── public/                  # Web entry (index.php) + assets
├── resources/               # Blade views, JS, CSS (Vite input)
├── routes/                  # web.php, auth.php, console.php
├── storage/, tests/         # PHPUnit tests
├── scripts/                 # CI/QA helper scripts (kit)
├── composer.json, package.json, vite.config.js, tailwind.config.js
```

## Quality Checks
- `/quality-audit` — Technical quality score /100
- `/business-gap-check` — Business coverage %
- `vendor/bin/pint` — Laravel code style (PSR-12)
- `php artisan test` — full PHPUnit suite
