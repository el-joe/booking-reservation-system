<?php

declare(strict_types=1);

namespace App\Services\Tenant\Accounting;

use App\Models\Account;

class AccountingBootstrapService
{
    public function seedDefaultChartOfAccounts(): void
    {
        $accounts = [
            // Assets
            ['code' => '1000', 'name' => 'Cash', 'type' => 'asset'],
            ['code' => '1100', 'name' => 'Accounts Receivable', 'type' => 'asset'],
            ['code' => '1200', 'name' => 'Prepaid Expenses', 'type' => 'asset'],
            // Liabilities
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability'],
            ['code' => '2100', 'name' => 'Deferred Revenue', 'type' => 'liability'],
            ['code' => '2200', 'name' => 'Tax Payable', 'type' => 'liability'],
            // Equity
            ['code' => '3000', 'name' => "Owner's Equity", 'type' => 'equity'],
            ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'equity'],
            // Revenue
            ['code' => '4000', 'name' => 'Booking Revenue', 'type' => 'revenue'],
            ['code' => '4100', 'name' => 'Add-on Revenue', 'type' => 'revenue'],
            ['code' => '4200', 'name' => 'Cancellation Fees', 'type' => 'revenue'],
            // Expenses
            ['code' => '5000', 'name' => 'Cost of Services', 'type' => 'expense'],
            ['code' => '5100', 'name' => 'Staff Costs', 'type' => 'expense'],
            ['code' => '5200', 'name' => 'Marketing', 'type' => 'expense'],
            ['code' => '5300', 'name' => 'Admin', 'type' => 'expense'],
        ];

        foreach ($accounts as $data) {
            Account::firstOrCreate(
                ['code' => $data['code']],
                array_merge($data, ['is_system' => true]),
            );
        }
    }
}
