<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        try {
            if (DB::connection()->getDriverName() === 'sqlite') {
                $pdo = DB::connection()->getPdo();
                // Register custom SQLite functions for compatibility with MySQL MONTH() and YEAR()
                $pdo->sqliteCreateFunction('MONTH', function ($date) {
                    if (!$date) return null;
                    return (int) date('m', strtotime($date));
                });
                $pdo->sqliteCreateFunction('YEAR', function ($date) {
                    if (!$date) return null;
                    return (int) date('Y', strtotime($date));
                });
            }
        } catch (\Exception $e) {
            // Ignore connection errors if DB is not booted in some unit tests
        }
    }
}
