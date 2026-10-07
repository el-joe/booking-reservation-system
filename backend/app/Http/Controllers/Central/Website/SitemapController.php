<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\BookingType;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $posts = BlogPost::published()->latest('published_at')->get(['slug', 'updated_at']);
        $categories = BookingType::cases();
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        // Static pages
        foreach (['/', '/pricing', '/blog', '/faq', '/contact', '/about', '/terms', '/privacy', '/help'] as $path) {
            $xml .= '<url><loc>'.url($path).'</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
        }
        // Blog posts
        foreach ($posts as $post) {
            $xml .= '<url><loc>'.route('central.website.blog.show', $post->slug).'</loc>';
            $xml .= '<lastmod>'.$post->updated_at->toAtomString().'</lastmod>';
            $xml .= '<changefreq>monthly</changefreq><priority>0.6</priority></url>';
        }
        // Categories
        foreach ($categories as $type) {
            $xml .= '<url><loc>'.route('central.website.category', $type->value).'</loc><changefreq>daily</changefreq><priority>0.7</priority></url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
