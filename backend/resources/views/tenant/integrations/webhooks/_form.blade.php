<div class="space-y-5">
    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Endpoint URL</label>
        <input type="url" name="url" value="{{ old('url', $webhook->url ?? '') }}" required
            class="block w-full rounded-lg border-gray-300 text-sm font-mono focus:border-blue-500 focus:ring-blue-500"
            placeholder="https://your-site.com/webhooks/receive">
        @error('url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">Events to Subscribe</label>
        @php
            $availableEvents = [
                'booking.created' => 'Booking Created',
                'booking.confirmed' => 'Booking Confirmed',
                'booking.cancelled' => 'Booking Cancelled',
                'payment.received' => 'Payment Received',
                'review.submitted' => 'Review Submitted',
            ];
            $selectedEvents = old('events', $webhook->events ?? []);
        @endphp
        <div class="space-y-2">
            @foreach ($availableEvents as $value => $label)
                <label class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="events[]" value="{{ $value }}"
                        {{ in_array($value, $selectedEvents) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700">{{ $label }}</span>
                    <code class="ml-auto text-xs text-gray-400">{{ $value }}</code>
                </label>
            @endforeach
        </div>
        @error('events') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div x-data="{ active: {{ isset($webhook) && $webhook->is_active ? 'true' : 'true' }} }">
        <label class="flex cursor-pointer items-center gap-3">
            <button type="button" @click="active = !active"
                :class="active ? 'bg-blue-600' : 'bg-gray-200'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 rounded-full transition-colors duration-200">
                <span :class="active ? 'translate-x-5' : 'translate-x-0'"
                    class="inline-block h-5 w-5 translate-x-0.5 transform rounded-full bg-white shadow transition duration-200 mt-0.5 ml-0.5"></span>
            </button>
            <input type="hidden" name="is_active" :value="active ? '1' : '0'">
            <span class="text-sm font-medium text-gray-700">Active</span>
        </label>
    </div>
</div>
