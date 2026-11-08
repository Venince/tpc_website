@extends('admin.layout')

@section('title', 'Add Achievement')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.services.show', $service) }}"
           class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-neo-surface shadow-neo-sm text-neo-ink/40 transition hover:shadow-neo-hover active:shadow-neo-inset-sm hover:text-tpc-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="min-w-0">
            <h1 class="text-base font-semibold text-tpc-ink">Add Achievement</h1>
            <p class="text-xs text-tpc-ink/50 truncate max-w-xs">{{ $service->title }}</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.services.achievements.store', $service) }}" method="POST" enctype="multipart/form-data"
          class="max-w-2xl"
          x-data="{
              max: 10,
              files: [],
              previews: [],
              dragging: false,
              get remaining() {
                  return this.max - this.files.length;
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
        @csrf

        <div class="rounded-2xl bg-neo-surface shadow-neo p-5 sm:p-6 space-y-5">

            {{-- Title --}}
            <div>
                <label for="title" class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                    Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus
                       placeholder="e.g. Regional Champion — TESDA Skills Competition"
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
                <input type="text" name="year" id="year" value="{{ old('year') }}" maxlength="50"
                       placeholder="e.g. 2026"
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
                          placeholder="A short note about this achievement..."
                          class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition resize-none">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Photos --}}
            <div>
                <div class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                    Photos <span class="ml-1 font-normal text-neo-ink/35">(optional · up to 10)</span>
                </div>

                {{-- Drop zone (the real file input sits invisibly on top, so a tap opens the picker) --}}
                <div @dragover.prevent="dragging = true"
                     @dragleave.prevent="dragging = false"
                     @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)"
                     :class="dragging ? 'shadow-neo-inset' : 'shadow-neo-inset-sm'"
                     class="relative flex flex-col items-center justify-center gap-2 rounded-xl bg-neo-bg px-4 py-8 text-center transition">
                    <div class="h-10 w-10 rounded-xl bg-neo-surface shadow-neo-sm flex items-center justify-center">
                        <svg class="h-5 w-5 text-tpc-primary/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-neo-ink/55" x-show="remaining > 0">Tap to select photos, or drag &amp; drop</p>
                    <p class="text-xs font-semibold text-neo-ink/55" x-show="remaining <= 0" x-cloak>Photo limit reached (10). Remove a photo to add another.</p>
                    <p class="text-[11px] text-neo-ink/35">JPG, PNG or WebP · max 5 MB each · up to 10 photos</p>
                    <input type="file" name="photos[]" multiple
                           accept="image/png,image/jpeg,image/webp"
                           x-ref="photoInput"
                           :tabindex="remaining <= 0 ? -1 : 0"
                           :class="remaining <= 0 ? 'pointer-events-none' : ''"
                           @change="addFiles($event.target.files)"
                           class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                </div>

                <p x-show="files.length" x-cloak class="mt-2 text-xs font-semibold text-tpc-primary">
                    <span x-text="files.length"></span>
                    <span x-text="files.length === 1 ? 'photo' : 'photos'"></span> selected
                    <span class="font-normal text-neo-ink/40">· <span x-text="remaining"></span> more allowed</span>
                </p>

                @error('photos')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror

                {{-- Previews --}}
                <div x-show="previews.length" x-cloak class="mt-3 grid grid-cols-3 sm:grid-cols-4 gap-2">
                    <template x-for="(src, i) in previews" :key="src">
                        <div class="relative aspect-square overflow-hidden rounded-xl bg-neo-bg shadow-neo-inset-sm">
                            <img :src="src" class="h-full w-full object-cover" alt="">
                            <button type="button" @click="remove(i)" title="Remove this photo"
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
            <div class="pt-2 border-t border-black/[0.06] flex flex-col-reverse sm:flex-row items-center gap-3">
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-tpc-primary px-6 py-2.5 text-sm font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Achievement
                </button>
                <a href="{{ route('admin.services.show', $service) }}"
                   class="w-full sm:w-auto text-center sm:text-left text-sm font-semibold text-neo-ink/40 hover:text-neo-ink transition py-2">
                    Cancel
                </a>
            </div>
        </div>
    </form>
@endsection
