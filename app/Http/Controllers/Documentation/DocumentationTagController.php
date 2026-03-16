<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentationTagRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\UpdateDocumentationTagRequest;
use App\Models\DocumentationTag;

class DocumentationTagController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $tags = DocumentationTag::withCount('documentations')->orderBy('name')->get();
        return view('documentation.tags.index', compact('tags'));
    }

    public function create()
    {
        $this->authorize('create', DocumentationTag::class);
        return view('documentation.tags.create');
    }

    public function store(StoreDocumentationTagRequest $request)
    {
        DocumentationTag::create($request->validated());

        return redirect()
            ->route('doc-tags.index')
            ->with('success', 'Tag created successfully.');
    }

    public function edit(DocumentationTag $tag)
    {
        $this->authorize('update', $tag);
        return view('documentation.tags.edit', compact('tag'));
    }

    public function update(UpdateDocumentationTagRequest $request, DocumentationTag $tag)
    {
        $this->authorize('update', $tag);
        $tag->update($request->validated());

        return redirect()
            ->route('doc-tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(DocumentationTag $tag)
    {
        $this->authorize('delete', $tag);
        $tag->documentations()->detach();
        $tag->delete();

        return redirect()
            ->route('doc-tags.index')
            ->with('success', 'Tag deleted.');
    }
}
