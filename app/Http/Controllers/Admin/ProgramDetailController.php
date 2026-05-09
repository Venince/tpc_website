<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramDetailController extends Controller
{
    // ── List all detail sections for a program ────────────────────────────

    public function index(Program $program)
    {
        $program->load('details');
        return view('admin.program-details.index', compact('program'));
    }

    // ── Create ────────────────────────────────────────────────────────────

    public function create(Program $program)
    {
        return view('admin.program-details.create', compact('program'));
    }

    public function store(Request $request, Program $program)
    {
        $type = $request->input('type', 'text');

        $base = $request->validate([
            'type'    => ['required', 'in:text,list,gallery'],
            'heading' => ['nullable', 'string', 'max:255'],
            'order'   => ['nullable', 'integer', 'min:0'],
        ]);

        $data = array_merge($base, [
            'program_id' => $program->id,
            'order'      => $base['order'] ?? ($program->details()->max('order') + 1),
        ]);

        if ($type === 'text') {
            $extra = $request->validate([
                'body' => ['required', 'string'],
            ]);
            $data['body'] = $extra['body'];

        } elseif ($type === 'list') {
            $extra = $request->validate([
                'items'   => ['required', 'array', 'min:1'],
                'items.*' => ['required', 'string', 'max:500'],
            ]);
            // Filter out empty strings submitted from the dynamic fields
            $data['items'] = array_values(array_filter($extra['items'], fn($v) => trim($v) !== ''));

        } elseif ($type === 'gallery') {
            $extra = $request->validate([
                'image'   => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'caption' => ['nullable', 'string', 'max:255'],
            ]);
            $data['image_path'] = $request->file('image')->store('program-gallery', 'public');
            $data['caption']    = $extra['caption'] ?? null;
        }

        ProgramDetail::create($data);

        return redirect()->route('admin.programs.details.index', $program)
            ->with('success', 'Section added.');
    }

    // ── Edit ──────────────────────────────────────────────────────────────

    public function edit(Program $program, ProgramDetail $detail)
    {
        abort_if($detail->program_id !== $program->id, 404);
        return view('admin.program-details.edit', compact('program', 'detail'));
    }

    public function update(Request $request, Program $program, ProgramDetail $detail)
    {
        abort_if($detail->program_id !== $program->id, 404);

        $type = $detail->type; // type is immutable after creation

        $base = $request->validate([
            'heading' => ['nullable', 'string', 'max:255'],
            'order'   => ['nullable', 'integer', 'min:0'],
        ]);

        $data = $base;

        if ($type === 'text') {
            $extra = $request->validate([
                'body' => ['required', 'string'],
            ]);
            $data['body'] = $extra['body'];

        } elseif ($type === 'list') {
            $extra = $request->validate([
                'items'   => ['required', 'array', 'min:1'],
                'items.*' => ['required', 'string', 'max:500'],
            ]);
            $data['items'] = array_values(array_filter($extra['items'], fn($v) => trim($v) !== ''));

        } elseif ($type === 'gallery') {
            $extra = $request->validate([
                'image'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'caption' => ['nullable', 'string', 'max:255'],
            ]);
            $data['caption'] = $extra['caption'] ?? null;

            if ($request->hasFile('image')) {
                if ($detail->image_path) {
                    Storage::disk('public')->delete($detail->image_path);
                }
                $data['image_path'] = $request->file('image')->store('program-gallery', 'public');
            }
        }

        $detail->update($data);

        return redirect()->route('admin.programs.details.index', $program)
            ->with('success', 'Section updated.');
    }

    // ── Delete ────────────────────────────────────────────────────────────

    public function destroy(Program $program, ProgramDetail $detail)
    {
        abort_if($detail->program_id !== $program->id, 404);

        if ($detail->image_path) {
            Storage::disk('public')->delete($detail->image_path);
        }

        $detail->delete();

        // Re-sequence
        $program->details()->orderBy('order')->get()
            ->each(fn($d, $i) => $d->update(['order' => $i + 1]));

        return redirect()->route('admin.programs.details.index', $program)
            ->with('success', 'Section deleted.');
    }

    // ── Reorder (AJAX) ────────────────────────────────────────────────────

    public function reorder(Request $request, Program $program)
    {
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->order as $position => $detailId) {
            ProgramDetail::where('id', $detailId)
                ->where('program_id', $program->id)
                ->update(['order' => $position + 1]);
        }

        return response()->json(['ok' => true]);
    }
}
