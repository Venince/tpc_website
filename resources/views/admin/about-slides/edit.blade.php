{{-- resources/views/admin/about-slides/edit.blade.php --}}
@extends('admin.layout')

@section('title', 'Edit About Slide')

@section('content')

{{-- Back link + header --}}
<div class="mb-7">
    <a href="{{ route('admin.about-slides.index') }}"
       class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-400 hover:text-tpc-primary transition mb-3">
        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Back to Slides
    </a>
    <div class="flex items-center gap-2">
        <div class="h-7 w-1 rounded-full bg-tpc-primary"></div>
        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Edit Slide</h1>
    </div>
</div>

<div class="max-w-2xl space-y-6">

    <form action="{{ route('admin.about-slides.update', $aboutSlide) }}" method="POST" enctype="multipart/form-data"
          x-data="{
              previewUrl: '{{ asset('storage/' . $aboutSlide->image_path) }}',
              hasNewImage: false,
              handleFile(e) {
                  const file = e.target.files[0];
                  if (!file) return;
                  this.hasNewImage = true;
                  const reader = new FileReader();
                  reader.onload = ev => this.previewUrl = ev.target.result;
                  reader.readAsDataURL(file);
              }
          }">
        @csrf @method('PUT')

        {{-- ── Image ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-800">Slide Image</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Leave unchanged to keep the current image</p>
                </div>
                <span x-show="hasNewImage"
                      class="text-[10px] font-bold bg-tpc-primary/10 text-tpc-primary px-2.5 py-1 rounded-full">
                    New image selected
                </span>
            </div>
            <div class="p-5">

                {{-- Preview --}}
                <div class="relative rounded-xl overflow-hidden border border-gray-100 bg-gray-50 mb-4 h-52">
                    <img :src="previewUrl"
                         alt="{{ $aboutSlide->title ?: 'Slide ' . $aboutSlide->sort_order }}"
                         class="w-full h-full object-cover">
                    <div x-show="hasNewImage"
                         class="absolute inset-x-0 bottom-0 bg-tpc-primary/90 text-white text-xs font-bold text-center py-1.5 tracking-wide">
                        Preview of new image
                    </div>
                </div>

                {{-- File input --}}
                <label for="image-input"
                       class="flex items-center gap-3 w-full cursor-pointer rounded-xl border border-dashed border-gray-200 bg-gray-50 hover:border-tpc-primary/40 hover:bg-tpc-primary/3 px-4 py-3 transition-all duration-200">
                    <div class="h-8 w-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center shrink-0">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-700">Replace image</p>
                        <p class="text-xs text-gray-400 truncate">JPG, PNG or WebP · max 4 MB · recommended ratio 2:1</p>
                    </div>
                    <input id="image-input" type="file" name="image" accept="image/*"
                           class="sr-only" @change="handleFile($event)">
                </label>

                @error('image')
                    <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- ── Details ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-800">Slide Details</h2>
            </div>
            <div class="p-5 space-y-5">

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Title <span class="normal-case font-normal text-gray-400">(optional)</span>
                    </label>
                    <input type="text" id="title" name="title"
                           value="{{ old('title', $aboutSlide->title) }}"
                           placeholder="e.g. Campus Life"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                    @error('title')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Visibility toggle --}}
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100"
                     x-data="{ active: {{ old('is_active', $aboutSlide->is_active) ? 'true' : 'false' }} }">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Visible on homepage</p>
                        <p class="text-xs text-gray-400 mt-0.5">Show this slide in the About section carousel</p>
                    </div>
                    <input type="hidden" name="is_active" :value="active ? '1' : '0'">
                    <button type="button" @click="active = !active"
                            :class="active ? 'bg-tpc-primary' : 'bg-gray-200'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                        <span :class="active ? 'translate-x-5' : 'translate-x-0.5'"
                              class="inline-block h-5 w-5 mt-0.5 rounded-full bg-white shadow-sm transform transition-transform duration-200"></span>
                    </button>
                </div>

                {{-- Sort order hint --}}
                <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 rounded-xl border border-blue-100">
                    <svg class="h-4 w-4 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <p class="text-xs text-blue-600">
                        This slide is currently at position <strong>#{{ $aboutSlide->sort_order }}</strong> in the carousel.
                    </p>
                </div>

            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center gap-3 pt-1">
            <a href="{{ route('admin.about-slides.index') }}"
               class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-600 hover:bg-gray-50 active:scale-95 transition-all">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-7 py-2.5 rounded-xl bg-tpc-primary text-white text-sm font-bold hover:bg-tpc-secondary active:scale-95 transition-all shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                Update Slide
            </button>
        </div>

    </form>

    {{-- ── Danger Zone ── --}}
    <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden"
         x-data="{ confirm: false }">
        <div class="px-5 py-4 border-b border-red-100">
            <h2 class="text-sm font-bold text-red-600">Danger Zone</h2>
        </div>
        <div class="p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-800">Delete this slide</p>
                    <p class="text-xs text-gray-400 mt-0.5">The image file will be permanently removed. This cannot be undone.</p>
                </div>
                <button type="button" @click="confirm = true"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-red-200 text-sm font-bold text-red-600 hover:bg-red-50 active:scale-95 transition-all shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Slide
                </button>
            </div>

            {{-- Inline confirm --}}
            <div x-show="confirm" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="mt-4 p-4 bg-red-50 rounded-xl border border-red-200">
                <p class="text-sm font-semibold text-red-700 mb-3">Are you sure? This slide and its image will be permanently deleted.</p>
                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.about-slides.destroy', $aboutSlide) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 active:scale-95 transition-all">
                            Yes, delete it
                        </button>
                    </form>
                    <button type="button" @click="confirm = false"
                            class="px-4 py-2 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-white active:scale-95 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
