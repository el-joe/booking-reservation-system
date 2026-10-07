@extends('layouts.central')

@section('title', 'Edit Blog Post')

@section('content')
    <x-page-header title="Edit Blog Post" subtitle="Update the blog post details." />
    <x-breadcrumb :items="[['label' => 'Blog', 'url' => route('central.blog.index')], ['label' => 'Edit']]" />

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('central.blog.update', $post) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Content</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm font-mono shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt <span class="text-gray-400 text-xs">(max 500 chars)</span></label>
                            <textarea name="excerpt" rows="2"
                                      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('excerpt', $post->excerpt) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Body <span class="text-red-500">*</span></label>
                            <textarea name="body" rows="16" required
                                      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono">{{ old('body', $post->body) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">SEO</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                SEO Title
                                <span class="text-gray-400 text-xs ml-1" id="seo-title-count">0/60</span>
                            </label>
                            <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $post->seo_title) }}" maxlength="60"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                SEO Description
                                <span class="text-gray-400 text-xs ml-1" id="seo-desc-count">0/160</span>
                            </label>
                            <textarea name="seo_description" id="seo_description" rows="3" maxlength="160"
                                      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('seo_description', $post->seo_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar options --}}
            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Publish</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $post->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Published At</label>
                            <input type="datetime-local" name="published_at"
                                   value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Author Name</label>
                            <input type="text" name="author_name" value="{{ old('author_name', $post->author_name) }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" name="category" value="{{ old('category', $post->category) }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags <span class="text-gray-400 text-xs">(comma-separated)</span></label>
                            <input type="text" name="tags"
                                   value="{{ old('tags', $post->tags ? implode(', ', $post->tags) : '') }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image URL</label>
                            <input type="url" name="cover_image" value="{{ old('cover_image', $post->cover_image) }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            @if($post->cover_image)
                            <img src="{{ $post->cover_image }}" alt="Cover" class="mt-2 rounded-lg h-20 w-full object-cover">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 text-xs text-gray-500 space-y-1">
                    <p>Created: {{ $post->created_at->format('M j, Y H:i') }}</p>
                    <p>Updated: {{ $post->updated_at->format('M j, Y H:i') }}</p>
                    <p>Read time: {{ $post->reading_time }}</p>
                    @if($post->status === 'published')
                    <a href="{{ route('central.website.blog.show', $post->slug) }}" target="_blank"
                       class="inline-flex items-center gap-1 text-indigo-600 hover:underline font-medium mt-1">
                        View live post ↗
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('central.blog.index') }}"
               class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                Save Changes
            </button>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    const seoTitle = document.getElementById('seo_title');
    const seoTitleCount = document.getElementById('seo-title-count');
    const seoDesc = document.getElementById('seo_description');
    const seoDescCount = document.getElementById('seo-desc-count');

    function updateCount(input, counter, max) {
        counter.textContent = input.value.length + '/' + max;
        counter.classList.toggle('text-red-500', input.value.length > max * 0.9);
    }

    seoTitle.addEventListener('input', () => updateCount(seoTitle, seoTitleCount, 60));
    seoDesc.addEventListener('input', () => updateCount(seoDesc, seoDescCount, 160));
    updateCount(seoTitle, seoTitleCount, 60);
    updateCount(seoDesc, seoDescCount, 160);
</script>
@endsection
