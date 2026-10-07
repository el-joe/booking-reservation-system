@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'selected' => '',
    'error' => null,
    'required' => false,
    'placeholder' => 'Select an option',
])

<div class="space-y-1">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'block w-full rounded-lg border px-3 py-2 text-sm text-gray-900 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 '
                . ($error ?? $errors->first($name) ? 'border-red-400 bg-red-50 focus:ring-red-500' : 'border-gray-300 bg-white')
        ]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $key => $label)
            <option value="{{ $key }}" @selected(old($name, $selected) == $key)>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @if ($error ?? $errors->first($name))
        <p class="text-xs text-red-600">{{ $error ?? $errors->first($name) }}</p>
    @endif
</div>
