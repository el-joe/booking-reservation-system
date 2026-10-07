<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Settings;

use App\Http\Controllers\Controller;
use App\Models\TenantSetting;
use App\Services\Tenant\Settings\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentSettingsController extends Controller
{
    public function __construct(private readonly SettingsService $settings) {}

    public function index(): View
    {
        $settings = $this->settings->getGroup('payment');

        // Mask sensitive keys for display
        foreach (['stripe_secret_key', 'paymob_api_key'] as $key) {
            if (! empty($settings[$key])) {
                $settings[$key.'_masked'] = str_repeat('*', max(0, strlen($settings[$key]) - 4)).substr($settings[$key], -4);
            }
        }

        return view('tenant.settings.payment', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled_gateways' => 'nullable|array',
            'enabled_gateways.*' => 'string|in:stripe,paypal,paymob,cash',
            'stripe_publishable_key' => 'nullable|string|max:255',
            'stripe_secret_key' => 'nullable|string|max:255',
            'paymob_api_key' => 'nullable|string|max:255',
            'default_currency' => 'required|string|max:10',
            'deposit_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $validated['enabled_gateways'] = json_encode($validated['enabled_gateways'] ?? []);

        $this->settings->setMany($validated);

        foreach (array_keys($validated) as $key) {
            TenantSetting::where('key', $key)->update(['group' => 'payment']);
        }

        return redirect()->route('tenant.settings.payment')
            ->with('success', 'Payment settings saved.');
    }
}
