<div class="space-y-4">
    <x-form-input
        name="name"
        label="Full Name"
        :value="old('name', $customer->name ?? '')"
        required
    />

    <x-form-input
        type="email"
        name="email"
        label="Email"
        :value="old('email', $customer->email ?? '')"
        required
    />

    <x-form-input
        type="tel"
        name="phone"
        label="Phone"
        :value="old('phone', $customer->phone ?? '')"
    />

    <x-form-input
        type="date"
        name="date_of_birth"
        label="Date of Birth"
        :value="old('date_of_birth', isset($customer) ? $customer->date_of_birth?->format('Y-m-d') : '')"
    />

    <x-form-select
        name="gender"
        label="Gender"
        :value="old('gender', $customer->gender ?? '')"
        :options="['' => 'Select gender', 'male' => 'Male', 'female' => 'Female', 'other' => 'Other']"
    />

    <x-form-textarea
        name="address"
        label="Address"
        :value="old('address', $customer->address ?? '')"
    />

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700" for="tags">Tags</label>
        <input
            type="text"
            id="tags"
            name="tags"
            value="{{ old('tags', isset($customer) && $customer->tags ? implode(', ', $customer->tags) : '') }}"
            placeholder="vip, repeat, corporate (comma-separated)"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        >
        <p class="mt-1 text-xs text-gray-500">Separate tags with commas.</p>
    </div>
</div>
