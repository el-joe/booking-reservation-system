<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Website;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function myBookings(Request $request): View
    {
        $customer = Auth::guard('customer')->user();
        $tab = $request->input('tab', 'upcoming');

        $query = Booking::query()
            ->where('customer_id', $customer->id)
            ->with(['resource.media']);

        $upcoming = (clone $query)->whereIn('status', ['pending', 'confirmed'])->latest('check_in')->paginate(10);
        $past = (clone $query)->where('status', 'completed')->latest('check_in')->paginate(10);
        $cancelled = (clone $query)->where('status', 'cancelled')->latest('check_in')->paginate(10);

        return view('tenant.website.account.my-bookings', compact('upcoming', 'past', 'cancelled', 'tab'));
    }

    public function bookingDetail(Booking $booking): View
    {
        $customer = Auth::guard('customer')->user();

        abort_if($booking->customer_id !== $customer->id, 403);

        $booking->load(['resource.media', 'items']);

        $hasReview = $booking->customer->reviews()->where('booking_id', $booking->id)->exists();

        return view('tenant.website.account.booking-detail', compact('booking', 'hasReview'));
    }

    public function cancelBooking(Booking $booking, Request $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        abort_if($booking->customer_id !== $customer->id, 403);

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->input('reason'),
        ]);

        return redirect()->route('tenant.account.bookings')->with('success', 'Booking cancelled successfully.');
    }

    public function profile(): View
    {
        $customer = Auth::guard('customer')->user();

        return view('tenant.website.account.profile', compact('customer'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date'],
        ]);

        $customer->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function wishlist(Request $request): View
    {
        $wishlist = $request->session()->get('wishlist', []);

        return view('tenant.website.account.wishlist', compact('wishlist'));
    }

    public function myReviews(): View
    {
        $customer = Auth::guard('customer')->user();

        $reviews = $customer->reviews()->with('resource')->latest()->get();

        return view('tenant.website.account.my-reviews', compact('reviews'));
    }
}
