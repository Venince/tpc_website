<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceAchievementController extends Controller
{
    private const MAX_PHOTOS = 10;
    private const PHOTO_DIR  = 'service-achievement-images';

    private function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'year'            => ['nullable', 'string', 'max:50'],
            'description'     => ['nullable', 'string', 'max:2000'],
            'photos'          => ['nullable', 'array', 'max:' . self::MAX_PHOTOS],
            'photos.*'        => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_images'   => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ];
    }

    private function messages(): array
    {
        return [
            'photos.max'        => 'You can upload up to ' . self::MAX_PHOTOS . ' photos at a time.',
            'photos.*.mimes'    => 'Each photo must be a JPG, PNG or WebP image.',
            'photos.*.max'      => 'Each photo may not be larger than 5 MB.',
            'photos.*.uploaded' => 'A photo failed to upload. It may be larger than the server upload limit.',
        ];
    }

    public function create(Service $service)
    {
        return view('admin.service-achievements.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        $data = $request->validate($this->rules(), $this->messages());

        unset($data['photos'], $data['remove_images']);

        // Add to the end of the list
        $data['order'] = ((int) $service->achievements()->max('order')) + 1;

        $achievement = $service->achievements()->create($data);

        $this->storePhotos($request, $achievement);

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Achievement added.');
    }

    public function edit(Service $service, ServiceAchievement $achievement)
    {
        abort_if($achievement->service_id !== $service->id, 404);

        $achievement->load('images');

        return view('admin.service-achievements.edit', compact('service', 'achievement'));
    }

    public function update(Request $request, Service $service, ServiceAchievement $achievement)
    {
        abort_if($achievement->service_id !== $service->id, 404);

        $data = $request->validate($this->rules(), $this->messages());

        unset($data['photos'], $data['remove_images']);

        // Photos flagged for removal (only ones that belong to this achievement)
        $removeIds = array_map('intval', (array) $request->input('remove_images', []));
        $toRemove  = $achievement->images()->whereIn('id', $removeIds)->get();

        // Enforce the overall cap: photos kept + photos being added
        $kept     = $achievement->images()->count() - $toRemove->count();
        $newCount = count((array) $request->file('photos', []));

        if ($kept + $newCount > self::MAX_PHOTOS) {
            return back()
                ->withInput()
                ->withErrors(['photos' => 'An achievement can have at most ' . self::MAX_PHOTOS . ' photos.']);
        }

        $achievement->update($data);

        foreach ($toRemove as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $this->storePhotos($request, $achievement);

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Achievement updated.');
    }

    public function destroy(Service $service, ServiceAchievement $achievement)
    {
        abort_if($achievement->service_id !== $service->id, 404);

        // DB rows cascade, but the files on disk must be removed manually
        foreach ($achievement->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $achievement->delete();

        return redirect()
            ->route('admin.services.show', $service)
            ->with('success', 'Achievement deleted.');
    }

    /**
     * Drag-and-drop reorder (AJAX).
     * Expects JSON body: { "order": [3, 1, 5, 2] }  (achievement IDs in the new order)
     */
    public function reorder(Request $request, Service $service)
    {
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->order as $position => $id) {
            $service->achievements()
                ->where('id', $id)
                ->update(['order' => $position + 1]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Store every uploaded photo as a related image, appended after the existing ones.
     */
    private function storePhotos(Request $request, ServiceAchievement $achievement): void
    {
        if (!$request->hasFile('photos')) {
            return;
        }

        $next = ((int) $achievement->images()->max('order')) + 1;

        foreach ($request->file('photos') as $file) {
            $achievement->images()->create([
                'path'  => $file->store(self::PHOTO_DIR, 'public'),
                'order' => $next++,
            ]);
        }
    }
}
