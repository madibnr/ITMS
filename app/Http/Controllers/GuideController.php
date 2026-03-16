<?php

namespace App\Http\Controllers;

use App\Models\GuideFaq;
use App\Models\GuideTip;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    // ─── Admin Index ────────────────────────────────────────────────

    public function index()
    {
        $tips = GuideTip::ordered()->get();
        $faqs = GuideFaq::ordered()->get();

        return view('guide.index', compact('tips', 'faqs'));
    }

    // ─── Tips CRUD ──────────────────────────────────────────────────

    public function createTip()
    {
        return view('guide.tip-form', ['tip' => null]);
    }

    public function storeTip(Request $request)
    {
        $data = $request->validate([
            'icon' => 'required|string|max:100',
            'icon_bg' => 'required|string|max:30',
            'icon_color' => 'required|string|max:30',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        GuideTip::create($data);

        return redirect()->route('guide.index')->with('success', 'Solusi mandiri berhasil ditambahkan.');
    }

    public function editTip(GuideTip $tip)
    {
        return view('guide.tip-form', compact('tip'));
    }

    public function updateTip(Request $request, GuideTip $tip)
    {
        $data = $request->validate([
            'icon' => 'required|string|max:100',
            'icon_bg' => 'required|string|max:30',
            'icon_color' => 'required|string|max:30',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', false);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $tip->update($data);

        return redirect()->route('guide.index')->with('success', 'Solusi mandiri berhasil diperbarui.');
    }

    public function destroyTip(GuideTip $tip)
    {
        $tip->delete();

        return redirect()->route('guide.index')->with('success', 'Solusi mandiri berhasil dihapus.');
    }

    // ─── FAQs CRUD ──────────────────────────────────────────────────

    public function createFaq()
    {
        return view('guide.faq-form', ['faq' => null]);
    }

    public function storeFaq(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        GuideFaq::create($data);

        return redirect()->route('guide.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function editFaq(GuideFaq $faq)
    {
        return view('guide.faq-form', compact('faq'));
    }

    public function updateFaq(Request $request, GuideFaq $faq)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', false);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $faq->update($data);

        return redirect()->route('guide.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroyFaq(GuideFaq $faq)
    {
        $faq->delete();

        return redirect()->route('guide.index')->with('success', 'FAQ berhasil dihapus.');
    }
}
