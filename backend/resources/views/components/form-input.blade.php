@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'error' => null,
    'placeholder' => '',
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
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'block w-full rounded-lg border px-3 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 '
                . ($error ?? $errors->first($name) ? 'border-red-400 bg-red-50 focus:ring-red-500' : 'border-gray-300 bg-white')
        ]) }}
    >
    @if ($error ?? $errors->first($name))
        <p class="text-xs text-red-600">{{ $error ?? $errors->first($name) }}</p>
    @endif
</div>
