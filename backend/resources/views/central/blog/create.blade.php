@extends('layouts.central')

@section('title', 'New Blog Post')

@section('content')
    <x-page-header title="New Blog Post" subtitle="Create a new post for the public blog." />
    <x-breadcrumb :items="[['label' => 'Blog', 'url' => route('central.blog.index')], ['label' => 'Create']]" />

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('central.blog.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Content</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-gray-400 text-xs">(auto-generated from title)</span></label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm font-mono shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt <span class="text-gray-400 text-xs">(max 500 chars)</span></label>
                            <textarea name="excerpt" rows="2"
                                      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('excerpt') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Body <span class="text-red-500">*</span></label>
                            <textarea name="body" rows="16" required
                                      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 font-mono">{{ old('body') }}</textarea>
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
                            <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title') }}" maxlength="60"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                SEO Description
                                <span class="text-gray-400 text-xs ml-1" id="seo-desc-count">0/160</span>
                            </label>
                            <textarea name="seo_description" id="seo_description" rows="3" maxlength="160"
                                      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('seo_description') }}</textarea>
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
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Published At</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at') }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-900">Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Author Name</label>
                            <input type="text" name="author_name" value="{{ old('author_name') }}" placeholder="Admin"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" name="category" value="{{ old('category') }}"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags <span class="text-gray-400 text-xs">(comma-separated)</span></label>
                            <input type="text" name="tags" value="{{ old('tags') }}" placeholder="booking, tips, guide"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image URL</label>
                            <input type="url" name="cover_image" value="{{ old('cover_image') }}" placeholder="https://…"
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
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
                Create Post
            </button>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    let slugEdited = slugInput.value.length > 0;

    slugInput.addEventListener('input', () => { slugEdited = true; });

    titleInput.addEventListener('input', () => {
        if (!slugEdited) {
            slugInput.value = titleInput.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
        }
    });

    // SEO counters
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
