@props([
    'status' => '',
    'type' => 'booking_status',
])

@php
    $colorMap = [
        // Booking status colors
        'pending'        => 'bg-yellow-100 text-yellow-800',
        'confirmed'      => 'bg-blue-100 text-blue-800',
        'checked_in'     => 'bg-indigo-100 text-indigo-800',
        'completed'      => 'bg-green-100 text-green-800',
        'cancelled'      => 'bg-red-100 text-red-800',
        'no_show'        => 'bg-gray-100 text-gray-700',
        'refunded'       => 'bg-purple-100 text-purple-800',
        // Payment status colors
        'paid'           => 'bg-green-100 text-green-800',
        'partially_paid' => 'bg-orange-100 text-orange-800',
        'failed'         => 'bg-red-100 text-red-800',
        // Resource status colors
        'active'         => 'bg-green-100 text-green-800',
        'inactive'       => 'bg-gray-100 text-gray-700',
        'maintenance'    => 'bg-yellow-100 text-yellow-800',
        'blocked'        => 'bg-red-100 text-red-800',
    ];

    $label = match ($type) {
        'booking_status'  => \App\Enums\BookingStatus::tryFrom($status)?->label() ?? $status,
        'payment_status'  => \App\Enums\PaymentStatus::tryFrom($status)?->label() ?? $status,
        'resource_status' => \App\Enums\ResourceStatus::tryFrom($status)?->label() ?? $status,
        default           => ucfirst(str_replace('_', ' ', $status)),
    };

    $classes = $colorMap[$status] ?? 'bg-gray-100 text-gray-700';
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>
