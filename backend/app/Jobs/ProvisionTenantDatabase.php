<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Tenant;
use App\Services\Tenant\Accounting\AccountingBootstrapService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class ProvisionTenantDatabase implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Tenant $tenant)
    {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        $this->tenant->run(function () {
            Artisan::call('migrate', [
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            Artisan::call('tenant:seed-roles');

            app(AccountingBootstrapService::class)->seedDefaultChartOfAccounts();
        });
    }
}
