@extends('layouts.tenant')

@section('title', 'Add Staff Member')

@section('content')
    <x-page-header title="Add Staff Member" subtitle="Create a new team member">
        <x-slot name="actions">
            <a href="{{ route('tenant.staff.index') }}"
               class="inline-flex items-center gap-x-1.5 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Cancel
            </a>
        </x-slot>
    </x-page-header>

    <div class="mx-auto max-w-3xl">
        <form method="POST" action="{{ route('tenant.staff.store') }}" class="space-y-6">
            @csrf

            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-sm font-semibold text-gray-900">Basic Information</h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-form-input name="name" label="Full Name" :value="old('name')" required />
                    <x-form-input name="email" label="Email Address" type="email" :value="old('email')" required />
                    <x-form-input name="phone" label="Phone" :value="old('phone')" />
                    <x-form-input name="role" label="Role" :value="old('role')" required placeholder="e.g. Receptionist, Manager" />
                    <x-form-input name="department" label="Department" :value="old('department')" placeholder="e.g. Front Desk, Operations" />
                    <x-form-input name="hire_date" label="Hire Date" type="date" :value="old('hire_date')" />

                    <x-form-select name="employment_type" label="Employment Type" :value="old('employment_type', 'full_time')" :options="[
                        'full_time' => 'Full Time',
                        'part_time' => 'Part Time',
                        'contract' => 'Contract',
                    ]" />

                    <x-form-select name="status" label="Status" :value="old('status', 'active')" :options="[
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]" />
                </div>

                <div class="mt-4">
                    <x-form-textarea name="bio" label="Bio" :value="old('bio')" rows="3" />
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <h3 class="mb-4 text-sm font-semibold text-gray-900">Emergency Contact</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <x-form-input name="emergency_contact[name]" label="Contact Name" :value="old('emergency_contact.name')" />
                    <x-form-input name="emergency_contact[phone]" label="Contact Phone" :value="old('emergency_contact.phone')" />
                    <x-form-input name="emergency_contact[relationship]" label="Relationship" :value="old('emergency_contact.relationship')" />
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('tenant.staff.index') }}"
                   class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Create Staff Member
                </button>
            </div>
        </form>
    </div>
@endsection
