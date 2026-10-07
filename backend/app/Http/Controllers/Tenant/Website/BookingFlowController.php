<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingFlowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function step1(Resource $resource): View
    {
        return view('tenant.website.booking.step1', compact('resource'));
    }

    public function step2(Request $request): View|RedirectResponse
    {
        $data = $request->validate([
            'resource_id' => ['required', 'exists:resources,id'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
        ]);

        $request->session()->put('booking_step1', $data);

        $resource = Resource::with('addOns')->findOrFail($data['resource_id']);

        return view('tenant.website.booking.step2', compact('resource', 'data'));
    }

    public function step3(Request $request): View|RedirectResponse
    {
        $step1 = $request->session()->get('booking_step1');
        if (! $step1) {
            return redirect()->route('tenant.home');
        }

        $addOns = $request->input('add_ons', []);
        $request->session()->put('booking_step2', ['add_ons' => $addOns]);

        return view('tenant.website.booking.step3', compact('step1'));
    }

    public function step4(Request $request): View|RedirectResponse
    {
        $step1 = $request->session()->get('booking_step1');
        if (! $step1) {
            return redirect()->route('tenant.home');
        }

        $guestData = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email'],
            'guest_phone' => ['nullable', 'string', 'max:50'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
        ]);

        $request->session()->put('booking_step3', $guestData);

        $resource = Resource::with('addOns')->findOrFail($step1['resource_id']);
        $step2 = $request->session()->get('booking_step2', []);

        return view('tenant.website.booking.step4', compact('resource', 'step1', 'step2', 'guestData'));
    }

    public function step5(Request $request): View|RedirectResponse
    {
        $step1 = $request->session()->get('booking_step1');
        if (! $step1) {
            return redirect()->route('tenant.home');
        }

        $resource = Resource::findOrFail($step1['resource_id']);

        return view('tenant.website.booking.step5', compact('resource', 'step1'));
    }

    public function confirmBooking(Request $request): RedirectResponse
    {
        $step1 = $request->session()->get('booking_step1');
        $step3 = $request->session()->get('booking_step3', []);

        if (! $step1) {
            return redirect()->route('tenant.home');
        }

        $customer = Auth::guard('customer')->user();

        $booking = Booking::create([
            'reference_number' => 'BK-'.strtoupper(uniqid()),
            'customer_id' => $customer->id,
            'resource_id' => $step1['resource_id'],
            'booking_type' => Resource::findOrFail($step1['resource_id'])->booking_type,
            'status' => 'pending',
            'check_in' => $step1['check_in'],
            'check_out' => $step1['check_out'],
            'guests_count' => $step1['guests'],
            'notes' => $step3['special_requests'] ?? null,
            'source' => 'website',
            'total_amount' => $request->input('total_amount', 0),
            'paid_amount' => 0,
        ]);

        $request->session()->forget(['booking_step1', 'booking_step2', 'booking_step3']);

        return redirect()->route('tenant.book.step6', $booking);
    }

    public function step6(Booking $booking): View
    {
        $booking->load(['resource', 'customer']);

        return view('tenant.website.booking.step6', compact('booking'));
    }
}
