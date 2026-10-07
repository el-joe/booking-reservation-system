<div class="space-y-4">
    <x-form-input
        name="name"
        label="Full Name"
        :value="old('name', $lead->name ?? '')"
        required
    />

    <x-form-input
        type="email"
        name="email"
        label="Email"
        :value="old('email', $lead->email ?? '')"
    />

    <x-form-input
        type="tel"
        name="phone"
        label="Phone"
        :value="old('phone', $lead->phone ?? '')"
    />

    <x-form-select
        name="booking_type"
        label="Booking Type"
        :value="old('booking_type', $lead->booking_type?->value ?? '')"
        :options="collect(\App\Enums\BookingType::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->prepend('Select type', '')->all()"
    />

    <x-form-select
        name="source"
        label="Source"
        :value="old('source', $lead->source ?? '')"
        :options="['' => 'Select source', 'website' => 'Website', 'referral' => 'Referral', 'walk_in' => 'Walk-in', 'phone' => 'Phone', 'social' => 'Social Media', 'other' => 'Other']"
        required
    />

    <x-form-select
        name="status"
        label="Status"
        :value="old('status', $lead->status ?? 'new')"
        :options="['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'converted' => 'Converted', 'lost' => 'Lost']"
    />

    <x-form-textarea
        name="notes"
        label="Notes"
        :value="old('notes', $lead->notes ?? '')"
    />
</div>
