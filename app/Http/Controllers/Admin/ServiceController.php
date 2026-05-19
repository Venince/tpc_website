<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::ordered()->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'is_active'      => ['nullable', 'boolean'],
            'order'          => ['nullable', 'integer', 'min:0'],
            'featured_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order']     = $request->integer('order', 0);
        $data['slug']      = $this->uniqueSlug($data['title']);

        if ($request->hasFile('featured_image')) {
            $data['featured_image_path'] = $request->file('featured_image')
                ->store('service-images', 'public');
        }

        $service = Service::create($data);

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Service created successfully!');
    }

    public function show(Service $service)
    {
        $service->load('contents');
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:255', Rule::unique('services', 'title')->ignore($service->id)],
            'description'    => ['nullable', 'string'],
            'is_active'      => ['nullable', 'boolean'],
            'order'          => ['nullable', 'integer', 'min:0'],
            'remove_image'   => ['nullable', 'boolean'],
            'featured_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['order']     = $request->integer('order', 0);

        // Re-slug only if title changed
        if ($service->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $service->id);
        }

        // Remove featured image
        if ($request->boolean('remove_image') && $service->featured_image_path) {
            Storage::disk('public')->delete($service->featured_image_path);
            $data['featured_image_path'] = null;
        }

        // Replace featured image
        if ($request->hasFile('featured_image')) {
            if ($service->featured_image_path) {
                Storage::disk('public')->delete($service->featured_image_path);
            }
            $data['featured_image_path'] = $request->file('featured_image')
                ->store('service-images', 'public');
        }

        $service->update($data);

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        // Delete featured image
        if ($service->featured_image_path) {
            Storage::disk('public')->delete($service->featured_image_path);
        }

        // Delete all content images
        foreach ($service->contents as $content) {
            if ($content->image_path) {
                Storage::disk('public')->delete($content->image_path);
            }
        }

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service deleted.');
    }

    // ── Private helpers ───────────────────────────────────────────────

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'service';
        $slug = $base;
        $i    = 1;

        while (
            Service::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
