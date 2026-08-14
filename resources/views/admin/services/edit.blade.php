@extends('admin.layout')

@section('title', 'Edit Service')

@section('page_actions')
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.services.show', $service) }}"
               class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-neo-surface shadow-neo-sm text-neo-ink/40 transition hover:shadow-neo-hover active:shadow-neo-inset-sm hover:text-tpc-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-base font-semibold text-tpc-ink">Edit Service</h1>
                <p class="text-xs text-tpc-ink/50 truncate max-w-xs">{{ $service->title }}</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
        @csrf @method('PATCH')

        <div class="rounded-2xl bg-neo-surface shadow-neo p-5 sm:p-6 space-y-5">

            {{-- Title --}}
            <div>
                <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                    Service Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $service->title) }}" required
                       class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition @error('title') ring-2 ring-red-300 @enderror">
                @error('title')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">Short Description</label>
                <textarea name="description" rows="3"
                            class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition resize-none text-justify">{{ old('description', $service->description) }}</textarea>
            </div>

            {{-- Social Media Links --}}
            @include('admin.services._social_links', ['existing' => old('social_links', $service->social_links ?? [])])

            {{-- Featured Image --}}
            <div>
                <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">Featured Image</label>

                @if ($service->featured_image_path)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $service->featured_image_path) }}"
                             class="h-24 w-40 rounded-xl object-cover shadow-neo-inset-sm bg-neo-bg" alt="">
                        <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-red-500">
                            Remove current image
                        </label>
                    </div>
                @endif

                <label for="featured_image_input"
                       class="flex items-center gap-3 w-full cursor-pointer rounded-xl bg-neo-bg shadow-neo-inset-sm hover:shadow-neo-inset px-4 py-3 transition-all duration-200">
                    <div class="h-8 w-8 rounded-lg bg-neo-surface shadow-neo-sm flex items-center justify-center shrink-0">
                        <svg class="h-4 w-4 text-neo-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-neo-ink/70">Replace image</p>
                        <p class="text-xs text-neo-ink/40 truncate">JPG, PNG or WebP · max 5 MB · leave blank to keep existing</p>
                    </div>
                    <input id="featured_image_input" type="file" name="featured_image" accept="image/png,image/jpeg,image/webp"
                           class="sr-only">
                </label>

                @error('featured_image')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active toggle --}}
            <div class="rounded-xl bg-neo-bg shadow-neo-inset-sm p-4">
                <label class="block text-xs font-bold text-neo-ink/60 mb-3">Visibility</label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="hidden" name="is_active" value="0">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-10 rounded-full bg-neo-surface-dim peer-checked:bg-tpc-primary transition-colors duration-200 h-[22px]"></div>
                        <div class="absolute top-0.5 left-0.5 h-[18px] w-[18px] rounded-full bg-white shadow-sm transition-all duration-200 peer-checked:translate-x-[18px]"></div>
                    </div>
                    <span class="text-sm text-neo-ink/70 group-hover:text-neo-ink transition">Active (visible on site)</span>
                </label>
            </div>

            {{-- Submit --}}
            <div class="flex flex-col-reverse sm:flex-row items-center gap-3 border-t border-black/[0.06] pt-5">
                <a href="{{ route('admin.services.show', $service) }}"
                   class="w-full sm:w-auto text-center text-sm font-semibold text-neo-ink/40 hover:text-neo-ink transition py-2">
                    Cancel
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-tpc-primary px-6 py-2.5 text-sm font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
@endsection
