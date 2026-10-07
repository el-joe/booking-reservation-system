<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Document::with('uploadedBy')
            ->when($request->entity_type, fn ($q) => $q->where('documentable_type', $request->entity_type))
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->when($request->boolean('expiring_soon'), fn ($q) => $q->expiringSoon(now()->addDays(30)))
            ->latest();

        $documents = $query->paginate(20);

        return view('tenant.documents.index', compact('documents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'documentable_type' => ['required', 'string'],
            'documentable_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:20480'],
            'category' => ['nullable', 'string', 'max:100'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $file = $request->file('file');
        $tenantId = tenant('id') ?? 'default';
        $path = $file->store("documents/{$tenantId}", 'local');

        Document::create([
            'documentable_type' => $validated['documentable_type'],
            'documentable_id' => $validated['documentable_id'],
            'name' => $validated['name'],
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'category' => $validated['category'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'uploaded_by_id' => auth()->id(),
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function show(Document $document): View
    {
        return view('tenant.documents.show', compact('document'));
    }

    public function download(Document $document): StreamedResponse
    {
        return Storage::disk('local')->download($document->file_path, $document->name);
    }

    public function destroy(Document $document): RedirectResponse
    {
        Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}
