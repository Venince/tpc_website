{{-- resources/views/admin/about-slides/create.blade.php --}}
@extends('admin.layout')

@section('title', 'Add About Slide')

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
        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Add New Slide</h1>
    </div>
</div>

<form action="{{ route('admin.about-slides.store') }}" method="POST" enctype="multipart/form-data"
      class="max-w-2xl space-y-6"
      x-data="{
          previewUrl: null,
          handleFile(e) {
              const file = e.target.files[0];
              if (!file) return;
              const reader = new FileReader();
              reader.onload = ev => this.previewUrl = ev.target.result;
              reader.readAsDataURL(file);
          }
      }">
    @csrf

    {{-- ── Image upload ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-800">Slide Image <span class="text-red-500">*</span></h2>
            <p class="text-xs text-gray-400 mt-0.5">JPG, PNG or WebP · max 4 MB · recommended ratio 2:1 (e.g. 1120 × 560)</p>
        </div>
        <div class="p-5">

            {{-- Drop zone / preview --}}
            <label for="image-input"
                   class="relative flex flex-col items-center justify-center w-full cursor-pointer rounded-xl border-2 border-dashed transition-all duration-200 overflow-hidden"
                   :class="previewUrl ? 'border-tpc-primary/30 bg-transparent h-56' : 'border-gray-200 bg-gray-50 hover:border-tpc-primary/40 hover:bg-tpc-primary/3 h-40'">

                {{-- Empty state --}}
                <div x-show="!previewUrl" class="flex flex-col items-center gap-2 py-8 text-gray-400">
                    <div class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-semibold text-gray-600">Click to upload</p>
                        <p class="text-xs text-gray-400">or drag and drop</p>
                    </div>
                </div>

                {{-- Preview --}}
                <template x-if="previewUrl">
                    <div class="relative w-full h-full">
                        <img :src="previewUrl" alt="Preview"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 hover:opacity-100 transition">
                            <p class="text-white text-xs font-bold bg-black/50 px-3 py-1.5 rounded-full">Click to change</p>
                        </div>
                    </div>
                </template>

                <input id="image-input" type="file" name="image" accept="image/*" required
                       class="sr-only"
                       @change="handleFile($event)">
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
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                       placeholder="e.g. Campus Life"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition">
                @error('title')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Visibility toggle --}}
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100"
                 x-data="{ active: {{ old('is_active', true) ? 'true' : 'false' }} }">
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
            </svg>
            Save Slide
        </button>
    </div>

</form>

@endsection
