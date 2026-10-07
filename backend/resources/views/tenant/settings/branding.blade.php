@extends('layouts.tenant')

@section('title', 'Branding Settings')

@section('content')
    <x-page-header title="Settings" subtitle="Configure your account" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                @include('tenant.settings.partials.sidebar')
            </div>
        </div>
        <div class="lg:col-span-3">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-6 text-lg font-semibold text-gray-900">Branding</h2>

                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('tenant.settings.branding.update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-5">
                        {{-- Logo --}}
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Logo</label>
                            @if (!empty($settings['logo']))
                                <img src="{{ asset('storage/'.$settings['logo']) }}" alt="Logo" class="mb-2 h-16 rounded-lg border border-gray-200 object-contain p-1">
                            @endif
                            <input type="file" name="logo" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Primary Color</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="primary_color" value="{{ old('primary_color', $settings['primary_color'] ?? '#3B82F6') }}"
                                        class="h-9 w-16 cursor-pointer rounded-lg border border-gray-300">
                                    <input type="text" id="primary_color_text" value="{{ old('primary_color', $settings['primary_color'] ?? '#3B82F6') }}"
                                        class="block flex-1 rounded-lg border-gray-300 text-sm" readonly>
                                </div>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Secondary Color</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="secondary_color" value="{{ old('secondary_color', $settings['secondary_color'] ?? '#6366F1') }}"
                                        class="h-9 w-16 cursor-pointer rounded-lg border border-gray-300">
                                    <input type="text" id="secondary_color_text" value="{{ old('secondary_color', $settings['secondary_color'] ?? '#6366F1') }}"
                                        class="block flex-1 rounded-lg border-gray-300 text-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Font Family</label>
                            <select name="font_family" class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach (['Inter' => 'Inter', 'Roboto' => 'Roboto', 'Open Sans' => 'Open Sans', 'Lato' => 'Lato', 'Poppins' => 'Poppins'] as $font => $label)
                                    <option value="{{ $font }}" {{ old('font_family', $settings['font_family'] ?? 'Inter') === $font ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Tagline</label>
                            <input type="text" name="tagline" value="{{ old('tagline', $settings['tagline'] ?? '') }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" rows="2"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $settings['address'] ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}"
                                class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <h3 class="mb-3 text-sm font-semibold text-gray-700">Social Links</h3>
                            <div class="space-y-3">
                                @foreach (['website' => 'Website URL', 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'twitter' => 'Twitter / X', 'whatsapp' => 'WhatsApp Number'] as $key => $label)
                                    <div class="flex items-center gap-3">
                                        <label class="w-32 text-sm text-gray-600">{{ $label }}</label>
                                        <input type="text" name="social_{{ $key }}" value="{{ old("social_{$key}", $settings["social_{$key}"] ?? '') }}"
                                            class="block flex-1 rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Save Branding
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[type=color]').forEach(picker => {
        const textId = picker.getAttribute('name') + '_text';
        const text = document.getElementById(textId);
        if (text) {
            picker.addEventListener('input', () => text.value = picker.value);
        }
    });
</script>
@endpush
