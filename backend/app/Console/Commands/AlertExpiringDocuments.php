<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AlertExpiringDocuments extends Command
{
    protected $signature = 'documents:alert-expiring';

    protected $description = 'Alert tenant admins about documents expiring in the next 30 days';

    public function handle(): int
    {
        $expiringSoon = Document::with(['documentable', 'uploadedBy'])
            ->expiringSoon(now()->addDays(30))
            ->get();

        if ($expiringSoon->isEmpty()) {
            $this->info('No documents expiring soon.');

            return self::SUCCESS;
        }

        $this->info("Found {$expiringSoon->count()} document(s) expiring soon.");

        foreach ($expiringSoon as $document) {
            Log::info('Document expiring soon', [
                'document_id' => $document->id,
                'name' => $document->name,
                'expires_at' => $document->expires_at?->toDateString(),
                'documentable_type' => $document->documentable_type,
                'documentable_id' => $document->documentable_id,
            ]);

            $this->line("  - {$document->name} expires on {$document->expires_at?->toDateString()}");
        }

        return self::SUCCESS;
    }
}
