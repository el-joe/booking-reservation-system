@extends('layouts.central-website')

@section('seo')
@endsection

@section('content')

<div x-data="{ yearly: false }">

{{-- Hero --}}
<section class="bg-gradient-to-b from-indigo-50 to-white pt-16 pb-8">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <p class="text-indigo-600 font-semibold text-sm uppercase tracking-wider mb-3">Transparent Pricing</p>
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-gray-900 mb-4" style="text-wrap:balance">Simple pricing for every business</h1>
        <p class="text-gray-500 text-lg mb-8">No setup fees. No hidden charges. Cancel anytime.</p>

        {{-- Billing Toggle --}}
        <div class="inline-flex items-center bg-gray-100 rounded-xl p-1 gap-1">
            <button @click="yearly = false" :class="!yearly ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm font-semibold transition-all">Monthly</button>
            <button @click="yearly = true" :class="yearly ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm font-semibold transition-all flex items-center gap-2">
                Yearly
                <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full">Save 20%</span>
            </button>
        </div>
    </div>
</section>

{{-- Plan Cards --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        @foreach($plans as $plan)
        <div class="relative rounded-3xl border-2 {{ $plan->is_featured ? 'border-indigo-600 shadow-xl shadow-indigo-100' : 'border-gray-200 shadow-sm' }} bg-white p-8 flex flex-col">
            @if($plan->is_featured)
            <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wide shadow">Most Popular</div>
            @endif

            <div class="mb-6">
                <h2 class="font-display text-xl font-bold text-gray-900 mb-1">{{ $plan->name }}</h2>
                @if($plan->tagline)
                <p class="text-sm text-gray-500 italic">{{ $plan->tagline }}</p>
                @endif
            </div>

            <div class="mb-6">
                <div class="flex items-end gap-1">
                    <span x-show="!yearly" class="font-display text-4xl font-bold text-gray-900">${{ number_format($plan->price, 0) }}</span>
                    <span x-show="yearly" x-cloak class="font-display text-4xl font-bold text-gray-900">${{ number_format($plan->price * 0.8 * 12, 0) }}</span>
                    <span x-show="!yearly" class="text-gray-400 text-sm mb-1.5">/month</span>
                    <span x-show="yearly" x-cloak class="text-gray-400 text-sm mb-1.5">/year</span>
                </div>
                <p x-show="yearly" x-cloak class="text-xs text-green-600 font-semibold mt-1">Save ${{ number_format($plan->price * 12 - $plan->price * 0.8 * 12, 0) }} per year</p>
            </div>

            @if($plan->description)
            <p class="text-sm text-gray-500 mb-6 leading-relaxed">{{ $plan->description }}</p>
            @endif

            <ul class="space-y-3 mb-8 flex-1">
                {{-- Limits --}}
                <li class="flex items-center gap-2.5 text-sm text-gray-700">
                    <svg class="h-4 w-4 text-indigo-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $plan->max_bookings > 0 ? number_format($plan->max_bookings).' bookings/month' : 'Unlimited bookings' }}
                </li>
                <li class="flex items-center gap-2.5 text-sm text-gray-700">
                    <svg class="h-4 w-4 text-indigo-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $plan->max_resources > 0 ? $plan->max_resources.' resources' : 'Unlimited resources' }}
                </li>
                <li class="flex items-center gap-2.5 text-sm text-gray-700">
                    <svg class="h-4 w-4 text-indigo-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $plan->max_staff > 0 ? $plan->max_staff.' staff accounts' : 'Unlimited staff' }}
                </li>
                {{-- Feature flags --}}
                @php $features = $plan->features ?? []; @endphp
                @foreach(['has_api'=>'API Access','has_whatsapp'=>'WhatsApp Notifications','has_custom_domain'=>'Custom Domain','has_erp'=>'ERP Integration','has_channel_manager'=>'Channel Manager'] as $key=>$label)
                <li class="flex items-center gap-2.5 text-sm {{ ($features[$key] ?? false) ? 'text-gray-700' : 'text-gray-300' }}">
                    @if($features[$key] ?? false)
                    <svg class="h-4 w-4 text-indigo-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <svg class="h-4 w-4 text-gray-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    @endif
                    {{ $label }}
                </li>
                @endforeach
            </ul>

            <a href="{{ route('central.website.register', ['plan_id' => $plan->id]) }}"
               class="block w-full text-center font-semibold py-3 rounded-xl transition text-sm {{ $plan->is_featured ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md' : 'bg-gray-50 hover:bg-gray-100 text-gray-900 border border-gray-200' }}">
                Get Started {{ $loop->first ? 'Free' : '' }} →
            </a>
        </div>
        @endforeach
    </div>

    <p class="text-center text-sm text-gray-400 mt-6">All plans include a 30-day free trial. No credit card required.</p>
</section>

{{-- Feature Comparison Table --}}
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="font-display text-2xl font-bold text-gray-900 text-center mb-8">Full feature comparison</h2>
    <div class="overflow-x-auto rounded-2xl border border-gray-200 shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-4 font-semibold text-gray-600">Feature</th>
                    @foreach($plans as $plan)
                    <th class="text-center px-4 py-4 font-bold {{ $plan->is_featured ? 'text-indigo-600' : 'text-gray-900' }}">{{ $plan->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach([
                    ['label'=>'Monthly Bookings','key'=>'max_bookings','type'=>'limit'],
                    ['label'=>'Resources','key'=>'max_resources','type'=>'limit'],
                    ['label'=>'Staff Accounts','key'=>'max_staff','type'=>'limit'],
                    ['label'=>'API Access','key'=>'has_api','type'=>'feature'],
                    ['label'=>'WhatsApp Notifications','key'=>'has_whatsapp','type'=>'feature'],
                    ['label'=>'Custom Domain','key'=>'has_custom_domain','type'=>'feature'],
                    ['label'=>'ERP Integration','key'=>'has_erp','type'=>'feature'],
                    ['label'=>'Channel Manager','key'=>'has_channel_manager','type'=>'feature'],
                ] as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-700">{{ $row['label'] }}</td>
                    @foreach($plans as $plan)
                    <td class="text-center px-4 py-4">
                        @if($row['type'] === 'limit')
                            @php $val = $plan->{$row['key']}; @endphp
                            <span class="font-semibold text-gray-900">{{ $val > 0 ? number_format($val) : '∞' }}</span>
                        @else
                            @if(($plan->features[$row['key']] ?? false))
                            <svg class="h-5 w-5 text-green-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <span class="text-gray-300 font-medium">—</span>
                            @endif
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

{{-- FAQ --}}
<section class="max-w-3xl mx-auto px-4 sm:px-6 py-12 pb-20">
    <h2 class="font-display text-2xl font-bold text-gray-900 text-center mb-8">Pricing FAQ</h2>
    <div x-data="{ open: null }" class="space-y-3">
        @foreach([
            ['q'=>'Can I change my plan later?','a'=>'Yes, you can upgrade or downgrade your plan at any time from your dashboard. Changes take effect immediately and billing is prorated.'],
            ['q'=>'Is there a free trial?','a'=>'Every plan starts with a 30-day free trial. No credit card required to sign up. You only pay when you decide to continue.'],
            ['q'=>'What happens if I exceed my booking limit?','a'=>'We will notify you when you reach 80% of your limit. You can upgrade your plan at any time. We do not cut off your service without warning.'],
            ['q'=>'Do you offer refunds?','a'=>'Yes, we offer a full refund within 30 days of your first payment if you are not satisfied. Contact our support team.'],
        ] as $i => $faq)
        <div class="border border-gray-200 rounded-2xl overflow-hidden">
            <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full flex items-center justify-between px-6 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 transition text-sm">
                {{ $faq['q'] }}
                <svg :class="open === {{ $i }} ? 'rotate-180' : ''" class="h-5 w-5 text-gray-400 transition-transform flex-shrink-0 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open === {{ $i }}" x-cloak class="px-6 pb-5 text-sm text-gray-500 leading-relaxed border-t border-gray-100">
                <p class="pt-4">{{ $faq['a'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

</div>
@endsection
