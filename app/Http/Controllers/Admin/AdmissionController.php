<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionSection;
use App\Models\AdmissionItem;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    // ── Sections ──────────────────────────────────────────────────────────

    public function index()
    {
        $sections = AdmissionSection::with('items')->orderBy('order')->get();
        return view('admin.admission.index', compact('sections'));
    }

    public function editSection(AdmissionSection $section)
    {
        return view('admin.admission.edit-section', compact('section'));
    }

    public function updateSection(Request $request, AdmissionSection $section)
    {
        $data = $request->validate([
            'label'      => ['required', 'string', 'max:255'],
            'note'       => ['nullable', 'string', 'max:2000'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $data['is_visible'] = $request->boolean('is_visible');
        $section->update($data);

        return redirect()->route('admin.admission.index')
            ->with('success', 'Section "' . $section->label . '" updated.');
    }

    // ── Items ─────────────────────────────────────────────────────────────

    public function createItem(AdmissionSection $section)
    {
        return view('admin.admission.create-item', compact('section'));
    }

    public function storeItem(Request $request, AdmissionSection $section)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['nullable', 'string', 'max:2000'],
        ]);

        // Place at end
        $data['order'] = $section->items()->max('order') + 1;
        $data['admission_section_id'] = $section->id;

        AdmissionItem::create($data);

        return redirect()->route('admin.admission.index')
            ->with('success', 'Item added to "' . $section->label . '".');
    }

    public function editItem(AdmissionSection $section, AdmissionItem $item)
    {
        abort_if($item->admission_section_id !== $section->id, 404);
        return view('admin.admission.edit-item', compact('section', 'item'));
    }

    public function updateItem(Request $request, AdmissionSection $section, AdmissionItem $item)
    {
        abort_if($item->admission_section_id !== $section->id, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['nullable', 'string', 'max:2000'],
        ]);

        $item->update($data);

        return redirect()->route('admin.admission.index')
            ->with('success', 'Item updated.');
    }

    public function destroyItem(AdmissionSection $section, AdmissionItem $item)
    {
        abort_if($item->admission_section_id !== $section->id, 404);
        $item->delete();

        // Re-sequence remaining items
        $section->items()->orderBy('order')->get()
            ->each(fn($it, $i) => $it->update(['order' => $i + 1]));

        return redirect()->route('admin.admission.index')
            ->with('success', 'Item deleted.');
    }

    // ── Reorder items (POST with array of IDs) ────────────────────────────

    public function reorderItems(Request $request, AdmissionSection $section)
    {
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->order as $position => $itemId) {
            AdmissionItem::where('id', $itemId)
                ->where('admission_section_id', $section->id)
                ->update(['order' => $position + 1]);
        }

        return response()->json(['ok' => true]);
    }
}
