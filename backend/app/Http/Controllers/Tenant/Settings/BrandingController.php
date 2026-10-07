<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Settings;

use App\Http\Controllers\Controller;
use App\Models\TenantSetting;
use App\Services\Tenant\Settings\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandingController extends Controller
{
    public function __construct(private readonly SettingsService $settings) {}

    public function index(): View
    {
        $settings = $this->settings->getGroup('branding');

        return view('tenant.settings.branding', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'primary_color' => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'font_family' => 'required|string|max:100',
            'tagline' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:30',
            'social_website' => 'nullable|url|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_whatsapp' => 'nullable|string|max:30',
            'logo' => 'nullable|image|max:2048',
        ]);

        unset($validated['logo']);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('branding', 'public');
        }

        $this->settings->setMany($validated);

        foreach (array_keys($validated) as $key) {
            TenantSetting::where('key', $key)->update(['group' => 'branding']);
        }

        return redirect()->route('tenant.settings.branding')
            ->with('success', 'Branding settings saved.');
    }
}
