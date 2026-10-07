<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Services\Central\SeoService;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setTitle('FAQ — '.config('app.name', 'BookEase'))
            ->setDescription('Find answers to common questions about our booking platform, billing, and technical setup.')
            ->setCanonical(url('/faq'));

        $faqs = Faq::active()->orderBy('category')->orderBy('sort_order')->get();
        $grouped = $faqs->groupBy('category');

        // FAQPage JSON-LD schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($f) => [
                '@type' => 'Question',
                'name' => $f->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
            ])->values()->toArray(),
        ];
        $seo->setSchema($schema);

        return view('central.website.faq', compact('grouped'));
    }
}
