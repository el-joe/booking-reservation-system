<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $countries = [
            ['name' => 'United States', 'iso2' => 'US', 'currency_code' => 'USD', 'currency_symbol' => '$', 'dial_code' => '+1'],
            ['name' => 'United Kingdom', 'iso2' => 'GB', 'currency_code' => 'GBP', 'currency_symbol' => '£', 'dial_code' => '+44'],
            ['name' => 'Germany', 'iso2' => 'DE', 'currency_code' => 'EUR', 'currency_symbol' => '€', 'dial_code' => '+49'],
            ['name' => 'France', 'iso2' => 'FR', 'currency_code' => 'EUR', 'currency_symbol' => '€', 'dial_code' => '+33'],
            ['name' => 'Italy', 'iso2' => 'IT', 'currency_code' => 'EUR', 'currency_symbol' => '€', 'dial_code' => '+39'],
            ['name' => 'Spain', 'iso2' => 'ES', 'currency_code' => 'EUR', 'currency_symbol' => '€', 'dial_code' => '+34'],
            ['name' => 'Saudi Arabia', 'iso2' => 'SA', 'currency_code' => 'SAR', 'currency_symbol' => '﷼', 'dial_code' => '+966'],
            ['name' => 'United Arab Emirates', 'iso2' => 'AE', 'currency_code' => 'AED', 'currency_symbol' => 'د.إ', 'dial_code' => '+971'],
            ['name' => 'Egypt', 'iso2' => 'EG', 'currency_code' => 'EGP', 'currency_symbol' => 'E£', 'dial_code' => '+20'],
            ['name' => 'Turkey', 'iso2' => 'TR', 'currency_code' => 'TRY', 'currency_symbol' => '₺', 'dial_code' => '+90'],
            ['name' => 'India', 'iso2' => 'IN', 'currency_code' => 'INR', 'currency_symbol' => '₹', 'dial_code' => '+91'],
            ['name' => 'Pakistan', 'iso2' => 'PK', 'currency_code' => 'PKR', 'currency_symbol' => '₨', 'dial_code' => '+92'],
            ['name' => 'Australia', 'iso2' => 'AU', 'currency_code' => 'AUD', 'currency_symbol' => 'A$', 'dial_code' => '+61'],
            ['name' => 'Canada', 'iso2' => 'CA', 'currency_code' => 'CAD', 'currency_symbol' => 'C$', 'dial_code' => '+1'],
            ['name' => 'Japan', 'iso2' => 'JP', 'currency_code' => 'JPY', 'currency_symbol' => '¥', 'dial_code' => '+81'],
            ['name' => 'China', 'iso2' => 'CN', 'currency_code' => 'CNY', 'currency_symbol' => '¥', 'dial_code' => '+86'],
            ['name' => 'Brazil', 'iso2' => 'BR', 'currency_code' => 'BRL', 'currency_symbol' => 'R$', 'dial_code' => '+55'],
            ['name' => 'Mexico', 'iso2' => 'MX', 'currency_code' => 'MXN', 'currency_symbol' => '$', 'dial_code' => '+52'],
            ['name' => 'South Africa', 'iso2' => 'ZA', 'currency_code' => 'ZAR', 'currency_symbol' => 'R', 'dial_code' => '+27'],
            ['name' => 'Nigeria', 'iso2' => 'NG', 'currency_code' => 'NGN', 'currency_symbol' => '₦', 'dial_code' => '+234'],
            ['name' => 'Kenya', 'iso2' => 'KE', 'currency_code' => 'KES', 'currency_symbol' => 'Ksh', 'dial_code' => '+254'],
            ['name' => 'Morocco', 'iso2' => 'MA', 'currency_code' => 'MAD', 'currency_symbol' => 'د.م.', 'dial_code' => '+212'],
            ['name' => 'Kuwait', 'iso2' => 'KW', 'currency_code' => 'KWD', 'currency_symbol' => 'د.ك', 'dial_code' => '+965'],
            ['name' => 'Qatar', 'iso2' => 'QA', 'currency_code' => 'QAR', 'currency_symbol' => 'ر.ق', 'dial_code' => '+974'],
            ['name' => 'Bahrain', 'iso2' => 'BH', 'currency_code' => 'BHD', 'currency_symbol' => 'BD', 'dial_code' => '+973'],
            ['name' => 'Oman', 'iso2' => 'OM', 'currency_code' => 'OMR', 'currency_symbol' => 'ر.ع.', 'dial_code' => '+968'],
            ['name' => 'Jordan', 'iso2' => 'JO', 'currency_code' => 'JOD', 'currency_symbol' => 'د.أ', 'dial_code' => '+962'],
            ['name' => 'Netherlands', 'iso2' => 'NL', 'currency_code' => 'EUR', 'currency_symbol' => '€', 'dial_code' => '+31'],
            ['name' => 'Sweden', 'iso2' => 'SE', 'currency_code' => 'SEK', 'currency_symbol' => 'kr', 'dial_code' => '+46'],
            ['name' => 'Singapore', 'iso2' => 'SG', 'currency_code' => 'SGD', 'currency_symbol' => 'S$', 'dial_code' => '+65'],
        ];

        $rows = array_map(fn (array $country) => array_merge($country, [
            'created_at' => $now,
            'updated_at' => $now,
        ]), $countries);

        DB::table('countries')->insertOrIgnore($rows);
    }
}
