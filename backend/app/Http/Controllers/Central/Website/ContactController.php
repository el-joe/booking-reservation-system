<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\Central\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setTitle('Contact Us — '.config('app.name', 'BookEase'))
            ->setDescription('Get in touch with our team. We typically respond within 24 hours.')
            ->setCanonical(url('/contact'));

        return view('central.website.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        // Rate limit: 5 attempts per minute per IP
        $key = 'contact:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors(['email' => "Too many submissions. Please wait {$seconds} seconds."])->withInput();
        }
        RateLimiter::hit($key, 60);

        // Honeypot check
        if ($request->filled('website')) {
            return redirect()->route('central.website.home');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ]);

        ContactMessage::create($validated);

        // Optionally notify admin by email (skip if mail not configured)
        try {
            $adminEmail = config('mail.from.address');
            if ($adminEmail) {
                Mail::raw(
                    "New contact message from {$validated['name']} ({$validated['email']}):\n\nSubject: {$validated['subject']}\n\n{$validated['message']}",
                    function ($m) use ($validated, $adminEmail) {
                        $m->to($adminEmail)->subject('New Contact Message: '.$validated['subject']);
                    }
                );
            }
        } catch (\Throwable) {
            // silently ignore mail failures
        }

        return redirect()->route('central.website.contact')
            ->with('success', "Thank you! Your message has been received. We'll get back to you within 24 hours.");
    }
}
