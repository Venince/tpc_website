@extends('admin.layout')

@section('title', 'Edit Achievement')

@php
    $existingImages = $achievement->images->map(fn ($img) => [
        'id'  => $img->id,
        'url' => asset('storage/' . $img->path),
    ])->values()->all();
@endphp

@section('page_actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.services.show', $service) }}"
           class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-neo-surface shadow-neo-sm text-neo-ink/40 transition hover:shadow-neo-hover active:shadow-neo-inset-sm hover:text-tpc-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="min-w-0">
            <h1 class="text-base font-semibold text-tpc-ink">Edit Achievement</h1>
            <p class="text-xs text-tpc-ink/50 truncate max-w-xs">{{ $service->title }}</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.services.achievements.update', [$service, $achievement]) }}" method="POST" enctype="multipart/form-data"
          class="max-w-2xl"
          x-data="{
              max: 10,
              existing: @js($existingImages),
              removeIds: [],
              files: [],
              previews: [],
              dragging: false,
              get keptCount() {
                  return this.existing.length - this.removeIds.length;
              },
              get remaining() {
                  return this.max - this.keptCount - this.files.length;
              },
              isRemoving(id) {
                  return this.removeIds.includes(id);
              },
              toggleRemove(id) {
                  if (this.isRemoving(id)) {
                      this.removeIds = this.removeIds.filter((x) => x !== id);
                  } else {
                      this.removeIds.push(id);
                  }
              },
              addFiles(list) {
                  const room = Math.max(this.remaining, 0);
                  Array.from(list).slice(0, room).forEach((f) => {
                      this.files.push(f);
                      this.previews.push(URL.createObjectURL(f));
                  });
                  this.sync();
              },
              remove(i) {
                  URL.revokeObjectURL(this.previews[i]);
                  this.files.splice(i, 1);
                  this.previews.splice(i, 1);
                  this.sync();
              },
              sync() {
                  const dt = new DataTransfer();
                  this.files.forEach((f) => dt.items.add(f));
                  this.$refs.photoInput.files = dt.files;
              }
          }">
        @csrf @method('PATCH')

        {{-- Photos marked for removal are sent as remove_images[] and deleted on save --}}
        <template x-for="id in removeIds" :key="id">
            <input type="hidden" name="remove_images[]" :value="id">
        </template>

        <div class="rounded-2xl bg-neo-surface shadow-neo p-5 sm:p-6 space-y-5">

            {{-- Title --}}
            <div>
                <label for="title" class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $achievement->title) }}" required
                       class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition @error('title') ring-2 ring-red-300 @enderror">
                @error('title')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Year --}}
            <div>
                <label for="year" class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                    Year <span class="ml-1 font-normal text-neo-ink/35">(optional)</span>
                </label>
                <input type="text" name="year" id="year" value="{{ old('year', $achievement->year) }}" maxlength="50"
                       class="w-full sm:w-48 rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition">
                @error('year')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                    Description <span class="ml-1 font-normal text-neo-ink/35">(optional)</span>
                </label>
                <textarea name="description" id="description" rows="4" maxlength="2000"
                          class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition resize-none">{{ old('description', $achievement->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Current photos --}}
            <div x-show="existing.length" x-cloak>
                <div class="block text-xs font-bold text-neo-ink/60 mb-2">
                    Current Photos
                    <span class="ml-1 font-normal text-neo-ink/35">
                        (<span x-text="keptCount"></span> kept<span x-show="removeIds.length"> · <span x-text="removeIds.length"></span> to remove</span>)
                    </span>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                    <template x-for="img in existing" :key="img.id">
                        <div class="relative aspect-square overflow-hidden rounded-xl bg-neo-bg shadow-neo-inset-sm">
                            <img :src="img.url" alt=""
                                 :class="isRemoving(img.id) ? 'opacity-30 grayscale' : ''"
                                 class="h-full w-full object-cover transition">

                            <span x-show="isRemoving(img.id)" x-cloak
                                  class="absolute bottom-1 left-1 rounded-full bg-red-600 px-1.5 py-0.5 text-[9px] font-bold text-white">
                                REMOVING
                            </span>

                            <button type="button" @click="toggleRemove(img.id)"
                                    :title="isRemoving(img.id) ? 'Keep this photo' : 'Remove this photo'"
                                    :class="isRemoving(img.id) ? 'bg-tpc-primary' : 'bg-black/60 hover:bg-red-600'"
                                    class="absolute top-1 right-1 flex h-7 w-7 items-center justify-center rounded-full text-white transition">
                                <svg x-show="!isRemoving(img.id)" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                                <svg x-show="isRemoving(img.id)" x-cloak class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Add more photos --}}
            <div>
                <div class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                    Add Photos <span class="ml-1 font-normal text-neo-ink/35">(optional · 10 total per achievement)</span>
                </div>

                <div @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)"
                     :class="dragging ? 'shadow-neo-inset' : 'shadow-neo-inset-sm'"
                     class="relative flex flex-col items-center justify-center gap-2 rounded-xl bg-neo-bg px-4 py-7 text-center transition">
                    <div class="h-10 w-10 rounded-xl bg-neo-surface shadow-neo-sm flex items-center justify-center">
                        <svg class="h-5 w-5 text-tpc-primary/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-neo-ink/55" x-show="remaining > 0">Tap to add photos, or drag &amp; drop</p>
                    <p class="text-xs font-semibold text-neo-ink/55" x-show="remaining <= 0" x-cloak>Photo limit reached. Remove a photo to add another.</p>
                    <p class="text-[11px] text-neo-ink/35">JPG, PNG or WebP · max 5 MB each · <span x-text="Math.max(remaining, 0)"></span> more allowed</p>
                    <input type="file" name="photos[]" multiple
                           accept="image/png,image/jpeg,image/webp"
                           x-ref="photoInput"
                           :tabindex="remaining <= 0 ? -1 : 0"
                           :class="remaining <= 0 ? 'pointer-events-none' : ''"
                           @change="addFiles($event.target.files)"
                           class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                </div>

                <p x-show="files.length" x-cloak class="mt-2 text-xs font-semibold text-tpc-primary">
                    <span x-text="files.length"></span> new
                    <span x-text="files.length === 1 ? 'photo' : 'photos'"></span> queued
                </p>

                @error('photos')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror

                <div x-show="previews.length" x-cloak class="mt-3 grid grid-cols-3 sm:grid-cols-4 gap-2">
                    <template x-for="(src, i) in previews" :key="src">
                        <div class="relative aspect-square overflow-hidden rounded-xl bg-neo-bg shadow-neo-inset-sm">
                            <img :src="src" class="h-full w-full object-cover" alt="">
                            <span class="absolute bottom-1 left-1 rounded-full bg-tpc-primary/85 px-1.5 py-0.5 text-[9px] font-bold text-white">NEW</span>
                            <button type="button" @click="remove(i)" title="Discard this photo"
                                    class="absolute top-1 right-1 flex h-7 w-7 items-center justify-center rounded-full bg-black/60 text-white transition hover:bg-red-600">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
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
