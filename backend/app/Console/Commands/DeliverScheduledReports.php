<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeliverScheduledReports extends Command
{
    protected $signature = 'reports:deliver-scheduled';

    protected $description = 'Deliver scheduled reports to configured recipients';

    public function handle(): int
    {
        Log::info('Scheduled report delivery would run here');
        $this->info('Scheduled report delivery would run here');

        return self::SUCCESS;
    }
}
