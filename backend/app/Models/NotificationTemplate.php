<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationChannel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_trigger',
        'channel',
        'subject',
        'body_html',
        'body_text',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'channel' => NotificationChannel::class,
        'is_active' => 'boolean',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(NotificationLog::class, 'template_id');
    }

    /** @return array<string> */
    public function getAvailableVariables(): array
    {
        return $this->variables ?? [];
    }

    public function renderBody(array $variables): string
    {
        $body = $this->body_html;

        foreach ($variables as $key => $value) {
            $body = str_replace('{{'.$key.'}}', (string) $value, $body);
            $body = str_replace('{{ '.$key.' }}', (string) $value, $body);
        }

        return $body;
    }

    public function renderSubject(array $variables): string
    {
        $subject = $this->subject ?? '';

        foreach ($variables as $key => $value) {
            $subject = str_replace('{{'.$key.'}}', (string) $value, $subject);
            $subject = str_replace('{{ '.$key.' }}', (string) $value, $subject);
        }

        return $subject;
    }
}
