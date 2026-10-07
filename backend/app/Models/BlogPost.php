<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;

    /** @var array<int, string> */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'author_name',
        'cover_image',
        'category',
        'tags',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    /** @param Builder<BlogPost> $query */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function readingTime(): Attribute
    {
        return Attribute::make(
            get: fn () => ceil(str_word_count(strip_tags($this->body)) / 200).' min read',
        );
    }
}
