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
    // Supported platforms config: key => [label, url_prefix, icon_svg_path]
    public const SOCIAL_PLATFORMS = [
        'facebook'  => ['label' => 'Facebook',  'prefix' => 'https://facebook.com/'],
        'instagram' => ['label' => 'Instagram', 'prefix' => 'https://instagram.com/'],
        'twitter'   => ['label' => 'X (Twitter)', 'prefix' => 'https://x.com/'],
        'youtube'   => ['label' => 'YouTube',   'prefix' => 'https://youtube.com/'],
        'tiktok'    => ['label' => 'TikTok',    'prefix' => 'https://tiktok.com/@'],
        'linkedin'  => ['label' => 'LinkedIn',  'prefix' => 'https://linkedin.com/'],
        'other'     => ['label' => 'Other',     'prefix' => ''],
    ];

    public function index()
    {
        $services = Service::ordered()->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $platforms = self::SOCIAL_PLATFORMS;
        return view('admin.services.create', compact('platforms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'description'       => ['nullable', 'string'],
            'is_active'         => ['nullable', 'boolean'],
            'featured_image'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'social_links'      => ['nullable', 'array'],
            'social_links.*.platform' => ['required_with:social_links', 'string', 'in:' . implode(',', array_keys(self::SOCIAL_PLATFORMS))],
            'social_links.*.url'      => ['required_with:social_links', 'url', 'max:500'],
            'social_links.*.label'    => ['nullable', 'string', 'max:100'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['slug']      = $this->uniqueSlug($data['title']);

        // Filter out empty social link rows
        $data['social_links'] = collect($request->input('social_links', []))
            ->filter(fn($link) => !empty($link['url']))
            ->values()
            ->toArray() ?: null;

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
        $platforms = self::SOCIAL_PLATFORMS;
        return view('admin.services.edit', compact('service', 'platforms'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255', Rule::unique('services', 'title')->ignore($service->id)],
            'description'       => ['nullable', 'string'],
            'is_active'         => ['nullable', 'boolean'],
            'remove_image'      => ['nullable', 'boolean'],
            'featured_image'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'social_links'      => ['nullable', 'array'],
            'social_links.*.platform' => ['required_with:social_links', 'string', 'in:' . implode(',', array_keys(self::SOCIAL_PLATFORMS))],
            'social_links.*.url'      => ['required_with:social_links', 'url', 'max:500'],
            'social_links.*.label'    => ['nullable', 'string', 'max:100'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($service->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $service->id);
        }

        // Filter out empty social link rows
        $data['social_links'] = collect($request->input('social_links', []))
            ->filter(fn($link) => !empty($link['url']))
            ->values()
            ->toArray() ?: null;

        if ($request->boolean('remove_image') && $service->featured_image_path) {
            Storage::disk('public')->delete($service->featured_image_path);
            $data['featured_image_path'] = null;
        }

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
        if ($service->featured_image_path) {
            Storage::disk('public')->delete($service->featured_image_path);
        }

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
