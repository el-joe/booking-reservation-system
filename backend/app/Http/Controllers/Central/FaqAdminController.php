<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = Faq::query();
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }
        $faqs = $query->orderBy('category')->orderBy('sort_order')->paginate(20)->withQueryString();
        $categories = Faq::select('category')->distinct()->pluck('category');

        return view('central.faq.index', compact('faqs', 'categories'));
    }

    public function create(): View
    {
        $categories = Faq::select('category')->distinct()->pluck('category');

        return view('central.faq.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        Faq::create($validated);

        return redirect()->route('central.faq.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq): View
    {
        $categories = Faq::select('category')->distinct()->pluck('category');

        return view('central.faq.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $faq->update($validated);

        return redirect()->route('central.faq.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('central.faq.index')->with('success', 'FAQ deleted.');
    }
}
