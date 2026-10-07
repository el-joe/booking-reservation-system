<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\DataTables\Tenant\NotificationLogDataTable;
use App\Enums\NotificationChannel;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationJob;
use App\Models\NotificationTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function templates(): View
    {
        $templates = NotificationTemplate::all();

        return view('tenant.notifications.templates.index', compact('templates'));
    }

    public function createTemplate(): View
    {
        $channels = NotificationChannel::cases();
        $eventTriggers = $this->getEventTriggers();

        return view('tenant.notifications.templates.create', compact('channels', 'eventTriggers'));
    }

    public function storeTemplate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'event_trigger' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body_html' => ['required', 'string'],
            'body_text' => ['nullable', 'string'],
            'variables' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ]);

        $validated['variables'] = $this->parseVariables($validated['event_trigger']);
        $validated['is_active'] = $request->boolean('is_active', true);

        NotificationTemplate::create($validated);

        return redirect()->route('tenant.notifications.templates')
            ->with('success', 'Notification template created successfully.');
    }

    public function editTemplate(NotificationTemplate $template): View
    {
        $channels = NotificationChannel::cases();
        $eventTriggers = $this->getEventTriggers();

        return view('tenant.notifications.templates.edit', compact('template', 'channels', 'eventTriggers'));
    }

    public function updateTemplate(NotificationTemplate $template, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'event_trigger' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'string'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body_html' => ['required', 'string'],
            'body_text' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['variables'] = $this->parseVariables($validated['event_trigger']);
        $validated['is_active'] = $request->boolean('is_active', false);

        $template->update($validated);

        return redirect()->route('tenant.notifications.templates')
            ->with('success', 'Notification template updated successfully.');
    }

    public function logs(NotificationLogDataTable $dataTable): mixed
    {
        return $dataTable->render('tenant.notifications.logs.index');
    }

    public function testSend(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'template_id' => ['required', 'exists:notification_templates,id'],
        ]);

        /** @var NotificationTemplate $template */
        $template = NotificationTemplate::findOrFail($validated['template_id']);

        $recipient = $request->user()->email ?? '';

        if (empty($recipient)) {
            return back()->with('error', 'No email address found for your account.');
        }

        SendNotificationJob::dispatch(
            channel: $template->channel->value,
            recipient: $recipient,
            subject: $template->subject ?? 'Test Notification',
            body: $template->body_html,
            templateId: $template->id,
            notifiableType: $request->user()->getMorphClass(),
            notifiableId: $request->user()->getKey(),
        );

        return back()->with('success', 'Test notification sent to '.$recipient.'.');
    }

    public function settings(): View
    {
        return view('tenant.notifications.settings');
    }

    /** @return array<string, array<string>> */
    private function getEventTriggers(): array
    {
        return [
            'booking.created' => ['reference_number', 'customer_name', 'resource_name', 'check_in', 'check_out', 'total_amount'],
            'booking.confirmed' => ['reference_number', 'customer_name', 'resource_name', 'check_in', 'check_out', 'total_amount'],
            'booking.cancelled' => ['reference_number', 'customer_name', 'cancellation_reason'],
            'booking.reminder' => ['reference_number', 'customer_name', 'resource_name', 'check_in'],
            'payment.received' => ['reference_number', 'amount', 'currency', 'invoice_number'],
        ];
    }

    /** @return array<string> */
    private function parseVariables(string $eventTrigger): array
    {
        return $this->getEventTriggers()[$eventTrigger] ?? [];
    }
}
