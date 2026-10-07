<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\Central\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request, SeoService $seo): View
    {
        $seo->setTitle('Blog — '.config('app.name', 'BookEase'))
            ->setDescription('Tips, guides and product updates from the BookEase team.')
            ->setCanonical(url('/blog'));

        $query = BlogPost::published()->latest('published_at');

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = BlogPost::published()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
        $featuredPost = BlogPost::published()->latest('published_at')->first();

        return view('central.website.blog.index', compact('posts', 'categories', 'featuredPost'));
    }

    public function show(string $slug, SeoService $seo): View
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        $seo->setTitle(($post->seo_title ?: $post->title).' — '.config('app.name', 'BookEase'))
            ->setDescription($post->seo_description ?: $post->excerpt ?: '')
            ->setCanonical(route('central.website.blog.show', $post->slug))
            ->setOgType('article')
            ->setOgImage($post->cover_image ?: '')
            ->setSchema([
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $post->title,
                'description' => $post->excerpt ?? '',
                'author' => ['@type' => 'Person', 'name' => $post->author_name],
                'datePublished' => $post->published_at?->toIso8601String(),
                'dateModified' => $post->updated_at->toIso8601String(),
                'url' => route('central.website.blog.show', $post->slug),
            ]);

        $related = BlogPost::published()
            ->where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->limit(3)
            ->get();

        return view('central.website.blog.show', compact('post', 'related'));
    }
}
