<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Settings;

use App\Http\Controllers\Controller;
use App\Models\TenantSetting;
use App\Services\Tenant\Settings\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingSettingsController extends Controller
{
    public function __construct(private readonly SettingsService $settings) {}

    public function index(): View
    {
        $settings = $this->settings->getGroup('booking');

        return view('tenant.settings.booking', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'auto_confirm_bookings' => 'boolean',
            'buffer_time_minutes' => 'required|integer|min:0',
            'min_advance_booking_hours' => 'required|integer|min:0',
            'max_advance_booking_days' => 'required|integer|min:1',
            'max_guests' => 'required|integer|min:1',
        ]);

        $validated['auto_confirm_bookings'] = $request->boolean('auto_confirm_bookings') ? '1' : '0';

        $this->settings->setMany($validated);

        foreach (array_keys($validated) as $key) {
            TenantSetting::where('key', $key)->update(['group' => 'booking']);
        }

        return redirect()->route('tenant.settings.booking')
            ->with('success', 'Booking settings saved.');
    }
}
