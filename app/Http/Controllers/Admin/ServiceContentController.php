<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceContentController extends Controller
{
    public function create(Service $service)
    {
        return view('admin.service-contents.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        $data = $request->validate([
            'type'          => ['required', 'in:text,image'],
            'heading'       => ['nullable', 'string', 'max:255'],
            'body'          => ['nullable', 'string', 'required_if:type,text'],
            'image'         => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:8192', 'required_if:type,image'],
            'image_caption' => ['nullable', 'string', 'max:255'],
        ]);

        // Determine order (append to end)
        $data['order']      = $service->contents()->max('order') + 1;
        $data['service_id'] = $service->id;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')
                ->store('service-content-images', 'public');
        }

        $service->contents()->create($data);

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Content block added!');
    }

    public function edit(Service $service, ServiceContent $content)
    {
        return view('admin.service-contents.edit', compact('service', 'content'));
    }

    public function update(Request $request, Service $service, ServiceContent $content)
    {
        $data = $request->validate([
            'type'          => ['required', 'in:text,image'],
            'heading'       => ['nullable', 'string', 'max:255'],
            'body'          => ['nullable', 'string'],
            'remove_image'  => ['nullable', 'boolean'],
            'image'         => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'image_caption' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->boolean('remove_image') && $content->image_path) {
            Storage::disk('public')->delete($content->image_path);
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($content->image_path) {
                Storage::disk('public')->delete($content->image_path);
            }
            $data['image_path'] = $request->file('image')
                ->store('service-content-images', 'public');
        }

        $content->update($data);

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Content block updated!');
    }

    public function destroy(Service $service, ServiceContent $content)
    {
        if ($content->image_path) {
            Storage::disk('public')->delete($content->image_path);
        }

        $content->delete();

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Content block deleted.');
    }

    /**
     * Handle drag-and-drop reorder via AJAX.
     * Expects JSON body: { "order": [3, 1, 5, 2] }  (array of content IDs in new order)
     */
    public function reorder(Request $request, Service $service)
    {
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->order as $position => $contentId) {
            $service->contents()
                ->where('id', $contentId)
                ->update(['order' => $position]);
        }

        return response()->json(['success' => true]);
    }
}
