<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Resource;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function store(Resource $resource, Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi', 'max:51200'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $fileType = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
        $path = $file->store("resources/{$resource->id}/media", 'public');

        $hasCover = $resource->media()->where('is_cover', true)->exists();

        $media = ResourceMedia::create([
            'resource_id' => $resource->id,
            'file_path' => $path,
            'file_type' => $fileType,
            'sort_order' => $resource->media()->max('sort_order') + 1,
            'is_cover' => ! $hasCover,
            'caption' => $request->input('caption'),
        ]);

        return response()->json([
            'id' => $media->id,
            'file_path' => $path,
            'url' => Storage::url($path),
            'file_type' => $fileType,
            'is_cover' => $media->is_cover,
        ]);
    }

    public function reorder(Resource $resource, Request $request): JsonResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach ($request->input('ids') as $index => $id) {
            ResourceMedia::where('id', $id)->where('resource_id', $resource->id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Resource $resource, ResourceMedia $media): RedirectResponse
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return back()->with('success', 'Media deleted successfully.');
    }

    public function setCover(Resource $resource, ResourceMedia $media): JsonResponse
    {
        $resource->media()->update(['is_cover' => false]);
        $media->update(['is_cover' => true]);

        return response()->json(['success' => true]);
    }
}
