@extends('admin.layout')

@section('title', 'Edit Service')

@section('page_actions')
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.services.show', $service) }}"
               class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:border-tpc-primary/30 hover:text-tpc-primary transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-base font-bold text-tpc-ink">Edit Service</h1>
                <p class="text-xs text-tpc-ink/50 truncate max-w-xs">{{ $service->title }}</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data" class="space-y-5 max-w-2xl">
        @csrf @method('PATCH')

        {{-- Title --}}
        <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5">
            <label class="block text-xs font-bold text-gray-600 mb-1.5">
                Service Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $service->title) }}" required
                   class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 @error('title') border-red-300 @enderror">
            @error('title')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5">
            <label class="block text-xs font-bold text-gray-600 mb-1.5">Short Description</label>
            <textarea name="description" rows="3"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 resize-none text-justify">{{ old('description', $service->description) }}</textarea>
        </div>

        {{-- Social Media Links --}}
        @include('admin.services._social_links', ['existing' => old('social_links', $service->social_links ?? [])])

        {{-- Featured Image --}}
        <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5">
            <label class="block text-xs font-bold text-gray-600 mb-1.5">Featured Image</label>

            @if ($service->featured_image_path)
                <div class="mb-3 flex items-center gap-4">
                    <img src="{{ asset('storage/' . $service->featured_image_path) }}"
                         class="h-24 w-40 rounded-xl object-cover border border-gray-200" alt="">
                    <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                        <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-red-500">
                        Remove current image
                    </label>
                </div>
            @endif

            <input type="file" name="featured_image" accept="image/png,image/jpeg,image/webp"
                   class="block w-full text-sm text-gray-500 file:mr-3 file:rounded-full file:border-0 file:bg-tpc-primary/10 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-tpc-primary hover:file:bg-tpc-primary/20 transition">
            <p class="mt-1 text-[11px] text-gray-400">JPG, PNG or WebP · max 5 MB · leave blank to keep existing</p>
        </div>

        {{-- Active toggle --}}
        <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5">
            <label class="block text-xs font-bold text-gray-600 mb-3">Visibility</label>
            <label class="flex items-center gap-3 cursor-pointer group">
                <input type="hidden" name="is_active" value="0">
                <div class="relative">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-10 rounded-full bg-gray-200 peer-checked:bg-tpc-primary transition-colors duration-200 h-[22px]"></div>
                    <div class="absolute top-0.5 left-0.5 h-[18px] w-[18px] rounded-full bg-white shadow-sm transition-all duration-200 peer-checked:translate-x-[18px]"></div>
                </div>
                <span class="text-sm text-gray-700 group-hover:text-gray-900 transition">Active (visible on site)</span>
            </label>
        </div>

        {{-- Submit --}}
        <div class="flex flex-col-reverse sm:flex-row items-center gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('admin.services.show', $service) }}"
               class="w-full sm:w-auto text-center text-sm font-semibold text-gray-400 hover:text-gray-600 transition py-2">
                Cancel
            </a>
            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-tpc-primary px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-tpc-secondary transition-all hover:shadow-md active:scale-[0.98]">
                Save Changes
            </button>
        </div>
    </form>
@endsection
