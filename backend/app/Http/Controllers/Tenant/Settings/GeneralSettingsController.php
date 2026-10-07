<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Settings;

use App\Http\Controllers\Controller;
use App\Models\TenantSetting;
use App\Services\Tenant\Settings\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralSettingsController extends Controller
{
    public function __construct(private readonly SettingsService $settings) {}

    public function index(): View
    {
        $settings = $this->settings->getGroup('general');

        return view('tenant.settings.general', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'timezone' => 'required|string|timezone',
            'currency' => 'required|string|max:10',
            'language' => 'required|string|max:10',
            'date_format' => 'required|string|max:50',
        ]);

        $this->settings->setMany($validated);

        foreach (array_keys($validated) as $key) {
            TenantSetting::where('key', $key)->update(['group' => 'general']);
        }

        return redirect()->route('tenant.settings.general')
            ->with('success', 'General settings saved.');
    }
}
