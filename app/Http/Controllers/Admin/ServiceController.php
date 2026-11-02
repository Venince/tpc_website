<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceAttachment;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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

    // Downloadable / printable attachments
    private const ATTACHMENT_MIMES  = 'pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png';
    private const ATTACHMENT_MAX_KB = 10240; // 10 MB per file
    private const ATTACHMENT_DIR    = 'service-attachments';

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
        $data = $request->validate(
            [
                'title'             => ['required', 'string', 'max:255'],
                'description'       => ['nullable', 'string'],
                'is_active'         => ['nullable', 'boolean'],
                'featured_image'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'social_links'      => ['nullable', 'array'],
                'social_links.*.platform' => ['required_with:social_links', 'string', 'in:' . implode(',', array_keys(self::SOCIAL_PLATFORMS))],
                'social_links.*.url'      => ['required_with:social_links', 'url', 'max:500'],
                'social_links.*.label'    => ['nullable', 'string', 'max:100'],
            ] + $this->attachmentRules(),
            $this->attachmentMessages(),
            $this->attachmentAttributes()
        );

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

        unset($data['new_attachments'], $data['existing_attachments']);

        $service = Service::create($data);

        $this->syncAttachments($request, $service);

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Service created successfully!');
    }

    public function show(Service $service)
    {
        $service->load(['contents', 'attachments']);
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $platforms = self::SOCIAL_PLATFORMS;
        $service->load('attachments');
        return view('admin.services.edit', compact('service', 'platforms'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate(
            [
                'title'             => ['required', 'string', 'max:255', Rule::unique('services', 'title')->ignore($service->id)],
                'description'       => ['nullable', 'string'],
                'is_active'         => ['nullable', 'boolean'],
                'remove_image'      => ['nullable', 'boolean'],
                'featured_image'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'social_links'      => ['nullable', 'array'],
                'social_links.*.platform' => ['required_with:social_links', 'string', 'in:' . implode(',', array_keys(self::SOCIAL_PLATFORMS))],
                'social_links.*.url'      => ['required_with:social_links', 'url', 'max:500'],
                'social_links.*.label'    => ['nullable', 'string', 'max:100'],
            ] + $this->attachmentRules(),
            $this->attachmentMessages(),
            $this->attachmentAttributes()
        );

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

        unset($data['new_attachments'], $data['existing_attachments']);

        $service->update($data);

        $this->syncAttachments($request, $service);

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

        // DB rows cascade, but the files on disk must be removed manually
        foreach ($service->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service deleted.');
    }

    // ── Attachment helpers ────────────────────────────────────────────

    private function attachmentRules(): array
    {
        $fileRule = ['nullable', 'file', 'mimes:' . self::ATTACHMENT_MIMES, 'max:' . self::ATTACHMENT_MAX_KB];

        return [
            'new_attachments'               => ['nullable', 'array', 'max:25'],
            'new_attachments.*.label'       => ['nullable', 'string', 'max:150'],
            'new_attachments.*.file'        => $fileRule,
            'existing_attachments'          => ['nullable', 'array'],
            'existing_attachments.*.label'  => ['nullable', 'string', 'max:150'],
            'existing_attachments.*.file'   => $fileRule,
            'existing_attachments.*.remove' => ['nullable', 'boolean'],
        ];
    }

    private function attachmentMessages(): array
    {
        return [
            'new_attachments.*.file.mimes'       => 'Each attachment must be a PDF, Word, Excel, PowerPoint, JPG or PNG file.',
            'new_attachments.*.file.max'         => 'Each attachment may not be larger than 10 MB.',
            'new_attachments.*.file.uploaded'    => 'An attachment failed to upload. It may be larger than the server upload limit.',
            'existing_attachments.*.file.mimes'  => 'Each replacement file must be a PDF, Word, Excel, PowerPoint, JPG or PNG file.',
            'existing_attachments.*.file.max'    => 'Each replacement file may not be larger than 10 MB.',
            'existing_attachments.*.file.uploaded' => 'A replacement file failed to upload. It may be larger than the server upload limit.',
        ];
    }

    private function attachmentAttributes(): array
    {
        return [
            'new_attachments.*.label'       => 'attachment name',
            'new_attachments.*.file'        => 'attachment file',
            'existing_attachments.*.label'  => 'attachment name',
            'existing_attachments.*.file'   => 'replacement file',
        ];
    }

    /**
     * Apply every attachment change posted by the form:
     *  - existing rows: rename, replace the file, or remove
     *  - new rows: upload and append
     */
    private function syncAttachments(Request $request, Service $service): void
    {
        $disk     = Storage::disk('public');
        $existing = $service->attachments()->get()->keyBy('id');

        // 1. Update / replace / remove existing attachments
        foreach ((array) $request->input('existing_attachments', []) as $id => $row) {
            /** @var ServiceAttachment|null $attachment */
            $attachment = $existing->get((int) $id);

            // Ignore ids that don't belong to this service
            if (!$attachment) {
                continue;
            }

            if (!empty($row['remove'])) {
                $disk->delete($attachment->file_path);
                $attachment->delete();
                continue;
            }

            $payload = [];
            $file    = $request->file("existing_attachments.{$id}.file");

            if ($file instanceof UploadedFile) {
                $disk->delete($attachment->file_path);
                $payload = $this->storeAttachmentFile($file);
            }

            $payload['label'] = $this->cleanLabel(
                $row['label'] ?? null,
                $payload['original_name'] ?? $attachment->original_name
            );

            $attachment->update($payload);
        }

        // 2. Add new attachments (rows without a file are ignored)
        $nextOrder = ((int) $service->attachments()->max('sort_order')) + 1;

        foreach ((array) $request->input('new_attachments', []) as $i => $row) {
            $file = $request->file("new_attachments.{$i}.file");

            if (!($file instanceof UploadedFile)) {
                continue;
            }

            $stored = $this->storeAttachmentFile($file);

            $service->attachments()->create($stored + [
                'label'      => $this->cleanLabel($row['label'] ?? null, $stored['original_name']),
                'sort_order' => $nextOrder++,
            ]);
        }
    }

    /**
     * Store an uploaded file on the public disk and return the DB columns for it.
     */
    private function storeAttachmentFile(UploadedFile $file): array
    {
        $size     = $file->getSize();
        $mimeType = $file->getMimeType();
        $original = $file->getClientOriginalName();

        return [
            'file_path'     => $file->store(self::ATTACHMENT_DIR, 'public'),
            'original_name' => Str::limit($original, 250, ''),
            'mime_type'     => $mimeType,
            'file_size'     => $size ?: 0,
        ];
    }

    /**
     * Use the typed label, or fall back to the file name without its extension.
     */
    private function cleanLabel(?string $label, string $fallbackFileName): string
    {
        $label = trim((string) $label);

        if ($label === '') {
            $label = pathinfo($fallbackFileName, PATHINFO_FILENAME);
        }

        return Str::limit($label !== '' ? $label : 'Attachment', 150, '');
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
