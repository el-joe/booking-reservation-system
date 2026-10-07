<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::withTrashed()->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->input('search').'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $posts = $query->paginate(15)->withQueryString();

        return view('central.blog.index', compact('posts'));
    }

    public function create(): View
    {
        return view('central.blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'cover_image' => ['nullable', 'url', 'max:500'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:160'],
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['title']);
        $validated['tags'] = $validated['tags'] ? array_map('trim', explode(',', $validated['tags'])) : null;
        $validated['author_name'] = $validated['author_name'] ?: 'Admin';

        BlogPost::create($validated);

        return redirect()->route('central.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog): View
    {
        return view('central.blog.edit', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'cover_image' => ['nullable', 'max:500'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:160'],
        ]);

        $validated['tags'] = $validated['tags'] ? array_map('trim', explode(',', $validated['tags'])) : null;

        $blog->update($validated);

        return redirect()->route('central.blog.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        $blog->delete();

        return redirect()->route('central.blog.index')->with('success', 'Post deleted.');
    }
}
