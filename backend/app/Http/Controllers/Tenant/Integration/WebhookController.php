<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Integration;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use App\Services\Tenant\Integration\WebhookDispatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebhookController extends Controller
{
    public function __construct(private readonly WebhookDispatchService $dispatchService) {}

    public function index(): View
    {
        $webhooks = Webhook::latest()->get();

        return view('tenant.integrations.webhooks.index', compact('webhooks'));
    }

    public function create(): View
    {
        return view('tenant.integrations.webhooks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'url' => 'required|url|max:500',
            'events' => 'required|array|min:1',
            'events.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['secret'] = $this->dispatchService->generateSecret();

        Webhook::create($validated);

        return redirect()->route('tenant.integrations.webhooks.index')
            ->with('success', 'Webhook created successfully.');
    }

    public function show(Webhook $webhook): View
    {
        return view('tenant.integrations.webhooks.show', compact('webhook'));
    }

    public function edit(Webhook $webhook): View
    {
        return view('tenant.integrations.webhooks.edit', compact('webhook'));
    }

    public function update(Request $request, Webhook $webhook): RedirectResponse
    {
        $validated = $request->validate([
            'url' => 'required|url|max:500',
            'events' => 'required|array|min:1',
            'events.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $webhook->update($validated);

        return redirect()->route('tenant.integrations.webhooks.index')
            ->with('success', 'Webhook updated.');
    }

    public function destroy(Webhook $webhook): RedirectResponse
    {
        $webhook->delete();

        return redirect()->route('tenant.integrations.webhooks.index')
            ->with('success', 'Webhook deleted.');
    }
}
