<?php

declare(strict_types=1);

namespace App\Services\Tenant\Settings;

use App\Models\TenantSetting;

class SettingsService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return TenantSetting::get($key, $default);
    }

    public function set(string $key, mixed $value): void
    {
        TenantSetting::set($key, $value);
    }

    public function setMany(array $data): void
    {
        TenantSetting::setMany($data);
    }

    public function getGroup(string $group): array
    {
        return TenantSetting::scopeGroup(TenantSetting::query(), $group)
            ->pluck('value', 'key')
            ->all();
    }
}
