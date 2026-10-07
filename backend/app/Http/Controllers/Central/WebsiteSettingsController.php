<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WebsiteSettingsController extends Controller
{
    /** @var array<int, string> */
    private array $settingKeys = [
        'site_name', 'site_tagline', 'site_description',
        'contact_email', 'contact_phone', 'contact_address',
        'social_twitter', 'social_linkedin', 'social_facebook', 'social_instagram',
        'google_analytics_id', 'meta_keywords',
        'logo_url', 'favicon_url',
    ];

    public function index(): View
    {
        $settings = collect($this->settingKeys)->mapWithKeys(
            fn ($key) => [$key => DB::table('central_settings')
                ->where('key', $key)->value('value') ?? '']
        );

        return view('central.settings.website', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'google_analytics_id' => ['nullable', 'string', 'max:50'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'logo_url' => ['nullable', 'url', 'max:500'],
            'favicon_url' => ['nullable', 'url', 'max:500'],
        ]);

        foreach ($validated as $key => $value) {
            DB::table('central_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value ?? '', 'updated_at' => now()]
            );
        }

        return back()->with('success', 'Website settings updated successfully.');
    }
}
