<?php

namespace App\Enums;

enum PlanFeature: string
{
    case MaxBookings = 'max_bookings';
    case MaxResources = 'max_resources';
    case MaxStaff = 'max_staff';
    case HasApi = 'has_api';
    case HasChannelManager = 'has_channel_manager';
    case HasErp = 'has_erp';
    case HasCustomDomain = 'has_custom_domain';
    case HasWhatsApp = 'has_whatsapp';
    case HasAdvancedReports = 'has_advanced_reports';
    case HasMultiLocation = 'has_multi_location';

    public function label(): string
    {
        return match ($this) {
            self::MaxBookings => 'Max Bookings',
            self::MaxResources => 'Max Resources',
            self::MaxStaff => 'Max Staff',
            self::HasApi => 'API Access',
            self::HasChannelManager => 'Channel Manager',
            self::HasErp => 'ERP Module',
            self::HasCustomDomain => 'Custom Domain',
            self::HasWhatsApp => 'WhatsApp Integration',
            self::HasAdvancedReports => 'Advanced Reports',
            self::HasMultiLocation => 'Multi-Location',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MaxBookings => 'Maximum number of bookings allowed per billing period (0 = unlimited)',
            self::MaxResources => 'Maximum number of bookable resources (rooms, tables, etc.)',
            self::MaxStaff => 'Maximum number of staff member accounts',
            self::HasApi => 'Access to the REST API for third-party integrations',
            self::HasChannelManager => 'Sync availability with OTA channels (Booking.com, Airbnb, etc.)',
            self::HasErp => 'Access to the built-in ERP modules (accounting, payroll, inventory)',
            self::HasCustomDomain => 'Map a custom domain to the tenant booking page',
            self::HasWhatsApp => 'Send booking confirmations and notifications via WhatsApp',
            self::HasAdvancedReports => 'Access to advanced analytics and exportable reports',
            self::HasMultiLocation => 'Manage multiple business locations under one account',
        };
    }
}
