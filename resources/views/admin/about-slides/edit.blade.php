{{-- resources/views/admin/about-slides/edit.blade.php --}}
@extends('admin.layout')

@section('title', 'Edit About Slide')

@section('content')
<div class="p-6 max-w-2xl">

    <div class="mb-6">
        <a href="{{ route('admin.about-slides.index') }}"
           class="text-sm text-gray-500 hover:text-tpc-primary transition">← Back to Slides</a>
        <h1 class="text-xl font-bold text-gray-800 mt-1">Edit Slide</h1>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.about-slides.update', $aboutSlide) }}" method="POST" enctype="multipart/form-data"
          class="bg-white border border-gray-200 rounded p-6 space-y-5">
        @csrf @method('PUT')

        {{-- Current image --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Image</label>
            <img src="{{ asset('storage/' . $aboutSlide->image_path) }}"
                 alt="{{ $aboutSlide->title ?: 'Slide ' . $aboutSlide->sort_order }}"
                 id="img-preview-src"
                 class="rounded border border-gray-200 object-contain max-h-60 w-full bg-gray-50">
        </div>

        {{-- Replace image --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Replace Image <span class="text-gray-400">(optional)</span>
            </label>
            <input type="file" name="image" accept="image/*"
                   class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-tpc-primary file:text-white file:font-bold file:cursor-pointer hover:file:bg-tpc-secondary transition">
            <p class="mt-1 text-xs text-gray-400">Leave blank to keep the current image. Recommended ratio 2:1 (e.g. 1120×560).</p>
        </div>

        {{-- Title --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Title <span class="text-gray-400">(optional — admin reference only)</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $aboutSlide->title) }}"
                   placeholder="e.g. Campus Life"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-tpc-primary">
        </div>

        {{-- Active toggle --}}
        <div class="flex items-center gap-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $aboutSlide->is_active) ? 'checked' : '' }}
                   class="h-4 w-4 rounded border-gray-300 text-tpc-primary focus:ring-tpc-primary">
            <label for="is_active" class="text-sm font-semibold text-gray-700">Visible on homepage</label>
        </div>

        <div class="pt-2 flex gap-3">
            <button type="submit"
                    class="bg-tpc-primary text-white text-sm font-bold px-6 py-2.5 rounded hover:bg-tpc-secondary transition">
                Update Slide
            </button>
            <a href="{{ route('admin.about-slides.index') }}"
               class="text-sm font-bold text-gray-500 px-4 py-2.5 rounded border border-gray-300 hover:bg-gray-50 transition">
                Cancel
            </a>
        </div>
    </form>

    {{-- Danger zone --}}
    <div class="mt-6 border border-red-200 rounded p-4 bg-red-50">
        <p class="text-sm font-bold text-red-600 mb-2">Delete this slide</p>
        <p class="text-xs text-red-400 mb-3">This action cannot be undone. The image file will also be removed.</p>
        <form action="{{ route('admin.about-slides.destroy', $aboutSlide) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this slide?')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="bg-red-600 text-white text-xs font-bold px-4 py-2 rounded hover:bg-red-700 transition">
                Delete Slide
            </button>
        </form>
    </div>
</div>

<script>
    document.querySelector('input[name="image"]').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => {
            document.getElementById('img-preview-src').src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection
