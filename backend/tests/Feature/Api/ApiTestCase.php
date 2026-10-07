<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Base test case for API tests.
 *
 * Uses DatabaseTransactions so each test runs in a transaction
 * that is rolled back, keeping the DB clean without re-running migrations.
 */
abstract class ApiTestCase extends TestCase
{
    use DatabaseTransactions;
}
