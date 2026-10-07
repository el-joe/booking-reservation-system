<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriptions — Central Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Subscriptions</h1>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm" id="subscriptions-table">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tenant</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Plan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Starts At</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Ends At</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Trial Ends</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subscriptions as $subscription)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $subscription->tenant_id }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $subscription->plan?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                            $statusColors = [
                                'active'    => 'bg-green-100 text-green-700',
                                'trial'     => 'bg-blue-100 text-blue-700',
                                'expired'   => 'bg-red-100 text-red-600',
                                'cancelled' => 'bg-gray-100 text-gray-500',
                            ];
                            $color = $statusColors[$subscription->status] ?? 'bg-gray-100 text-gray-500';
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $subscription->starts_at?->format('Y-m-d') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $subscription->ends_at?->format('Y-m-d') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $subscription->trial_ends_at?->format('Y-m-d') ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('central.subscriptions.update', $subscription) }}"
                                  class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="plan_id"
                                        class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-400">
                                    @foreach(\App\Models\Plan::where('is_active', true)->get() as $plan)
                                    <option value="{{ $plan->id }}" {{ $plan->id === $subscription->plan_id ? 'selected' : '' }}>
                                        {{ $plan->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                        class="text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                    Change
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">No subscriptions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $subscriptions->links() }}
        </div>
        @endif
    </div>
</div>
</body>
</html>
