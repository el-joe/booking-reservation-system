<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Settings;

use App\Http\Controllers\Controller;
use App\Models\CentralSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GlobalSettingsController extends Controller
{
    public function index(): View
    {
        $settings = CentralSetting::where('group', 'general')->pluck('value', 'key');

        return view('central.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email_driver' => ['nullable', 'string', 'max:100'],
            'email_host' => ['nullable', 'string', 'max:255'],
            'email_port' => ['nullable', 'integer'],
            'email_username' => ['nullable', 'string', 'max:255'],
            'email_password' => ['nullable', 'string', 'max:255'],
            'email_encryption' => ['nullable', 'string', 'in:tls,ssl,none'],
            'sms_driver' => ['nullable', 'string', 'max:100'],
            'sms_api_key' => ['nullable', 'string', 'max:255'],
            'sms_sender_id' => ['nullable', 'string', 'max:100'],
            'booking_types' => ['nullable', 'array'],
        ]);

        foreach ($data as $key => $value) {
            CentralSetting::set($key, is_array($value) ? json_encode($value) : $value, 'general');
        }

        return back()->with('success', 'General settings saved.');
    }

    public function branding(): View
    {
        $settings = CentralSetting::where('group', 'branding')->pluck('value', 'key');

        return view('central.settings.branding', compact('settings'));
    }

    public function updateBranding(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'platform_name' => ['nullable', 'string', 'max:100'],
            'primary_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:512'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('branding', 'public');
            unset($data['logo']);
        }

        if ($request->hasFile('favicon')) {
            $data['favicon_path'] = $request->file('favicon')->store('branding', 'public');
            unset($data['favicon']);
        }

        foreach ($data as $key => $value) {
            CentralSetting::set($key, $value, 'branding');
        }

        return back()->with('success', 'Branding settings saved.');
    }

    public function maintenance(): View
    {
        $globalMaintenance = (bool) CentralSetting::get('global_maintenance', false);
        $tenants = DB::table('tenants')->select('id', 'name', 'data')->get()->map(function ($tenant) {
            $data = is_string($tenant->data) ? json_decode($tenant->data, true) : (array) $tenant->data;
            $tenant->maintenance = (bool) ($data['maintenance_mode'] ?? false);

            return $tenant;
        });

        return view('central.settings.maintenance', compact('globalMaintenance', 'tenants'));
    }

    public function toggleMaintenance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'scope' => ['required', 'in:global,tenant'],
            'tenant_id' => ['required_if:scope,tenant', 'nullable', 'integer'],
            'enabled' => ['required', 'boolean'],
        ]);

        if ($validated['scope'] === 'global') {
            CentralSetting::set('global_maintenance', $validated['enabled'] ? '1' : '0', 'maintenance');
        } else {
            $tenant = DB::table('tenants')->find($validated['tenant_id']);
            if ($tenant) {
                $data = is_string($tenant->data) ? json_decode($tenant->data, true) : (array) $tenant->data;
                $data['maintenance_mode'] = (bool) $validated['enabled'];
                DB::table('tenants')->where('id', $validated['tenant_id'])->update([
                    'data' => json_encode($data),
                ]);
            }
        }

        return back()->with('success', 'Maintenance mode updated.');
    }

    public function auditLog(): View
    {
        $events = DB::table('central_audit_logs')
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('central.settings.audit-log', compact('events'));
    }
}
