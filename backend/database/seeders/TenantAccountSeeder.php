<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Services\Tenant\Accounting\AccountingBootstrapService;
use Illuminate\Database\Seeder;

class TenantAccountSeeder extends Seeder
{
    public function run(): void
    {
        if (Account::count() === 0) {
            app(AccountingBootstrapService::class)->seedDefaultChartOfAccounts();
        }
    }
}
