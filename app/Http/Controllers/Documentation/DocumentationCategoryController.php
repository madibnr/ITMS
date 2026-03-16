<?php

namespace App\Http\Controllers\Documentation;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentationCategoryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\UpdateDocumentationCategoryRequest;
use App\Models\DocumentationCategory;

class DocumentationCategoryController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $categories = DocumentationCategory::withCount('documentations')->orderBy('name')->get();
        return view('documentation.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', DocumentationCategory::class);
        return view('documentation.categories.create');
    }

    public function store(StoreDocumentationCategoryRequest $request)
    {
        DocumentationCategory::create($request->validated());

        return redirect()
            ->route('doc-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(DocumentationCategory $category)
    {
        $this->authorize('update', $category);
        return view('documentation.categories.edit', compact('category'));
    }

    public function update(UpdateDocumentationCategoryRequest $request, DocumentationCategory $category)
    {
        $this->authorize('update', $category);
        $category->update($request->validated());

        return redirect()
            ->route('doc-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(DocumentationCategory $category)
    {
        $this->authorize('delete', $category);

        if ($category->documentations()->count() > 0) {
            return back()->with('error', 'Cannot delete category that has documentation.');
        }

        $category->delete();

        return redirect()
            ->route('doc-categories.index')
            ->with('success', 'Category deleted.');
    }
}
