<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\NotificationChannel;
use App\Models\NotificationTemplate;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NotificationTemplateTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function test_template_renders_variables(): void
    {
        $template = NotificationTemplate::create([
            'name' => 'Booking Confirmation',
            'event_trigger' => 'booking.confirmed',
            'channel' => NotificationChannel::Email,
            'subject' => 'Your booking is confirmed',
            'body_html' => 'Dear {{customer_name}}, your booking {{reference_number}} is confirmed.',
            'is_active' => true,
        ]);

        $result = $template->renderBody([
            'customer_name' => 'Ahmed',
            'reference_number' => 'BK-2026-ABC123',
        ]);

        $this->assertEquals(
            'Dear Ahmed, your booking BK-2026-ABC123 is confirmed.',
            $result
        );
    }

    #[Test]
    public function test_template_renders_subject(): void
    {
        $template = NotificationTemplate::create([
            'name' => 'Booking Reminder',
            'event_trigger' => 'booking.reminder',
            'channel' => NotificationChannel::Email,
            'subject' => 'Reminder: {{customer_name}}, your booking {{reference_number}} is tomorrow.',
            'body_html' => 'See you tomorrow.',
            'is_active' => true,
        ]);

        $result = $template->renderSubject([
            'customer_name' => 'Sara',
            'reference_number' => 'BK-2026-XYZ789',
        ]);

        $this->assertEquals(
            'Reminder: Sara, your booking BK-2026-XYZ789 is tomorrow.',
            $result
        );
    }
}
