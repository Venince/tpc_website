@extends('admin.layout')

@section('title', 'Create Service')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.services.index') }}"
           class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:border-tpc-primary/30 hover:text-tpc-primary transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-base font-bold text-tpc-ink">Create Service</h1>
            <p class="text-xs text-tpc-ink/50">Add a new service to the website</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data"
          class="max-w-2xl"
          x-data="{
              imagePreview: null,
              handleImage(e) {
                  const file = e.target.files[0];
                  if (!file) return;
                  const reader = new FileReader();
                  reader.onload = (ev) => this.imagePreview = ev.target.result;
                  reader.readAsDataURL(file);
              }
          }">
        @csrf

        <div class="space-y-5">

            {{-- Title --}}
            <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5">
                <label class="block text-xs font-bold text-gray-600 mb-1.5">
                    Service Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="e.g. Technical Vocational Training"
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 transition @error('title') border-red-300 bg-red-50/30 @enderror">
                @error('title')
                    <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5">
                <label class="block text-xs font-bold text-gray-600 mb-1.5">
                    Short Description
                    <span class="ml-1 font-normal text-gray-400">(optional)</span>
                </label>
                <textarea name="description" rows="3"
                          placeholder="A brief summary shown in the navbar tooltip and page header…"
                          class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 resize-none transition @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Social Media Links --}}
            @include('admin.services._social_links', ['existing' => old('social_links', [])])

            {{-- Featured Image --}}
            <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5">
                <label class="block text-xs font-bold text-gray-600 mb-3">Featured Image</label>

                <div x-show="imagePreview" x-transition class="mb-3">
                    <div class="relative inline-block rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                        <img :src="imagePreview" class="h-36 w-full max-w-xs object-cover" alt="Preview">
                        <button type="button"
                                @click="imagePreview = null; $refs.imageInput.value = ''"
                                class="absolute top-2 right-2 h-6 w-6 rounded-full bg-black/60 flex items-center justify-center text-white hover:bg-black/80 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <label x-show="!imagePreview"
                       class="flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-200 bg-white px-4 py-8 cursor-pointer hover:border-tpc-primary/40 hover:bg-tpc-primary/[0.02] transition group">
                    <div class="h-10 w-10 rounded-xl bg-gray-100 group-hover:bg-tpc-primary/8 flex items-center justify-center transition">
                        <svg class="h-5 w-5 text-gray-400 group-hover:text-tpc-primary/60 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-semibold text-gray-500 group-hover:text-tpc-primary/70 transition">Click to upload image</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">JPG, PNG or WebP · max 5 MB</p>
                    </div>
                    <input type="file" name="featured_image" accept="image/png,image/jpeg,image/webp"
                           x-ref="imageInput"
                           @change="handleImage($event)"
                           class="sr-only">
                </label>

                <div x-show="imagePreview" class="mt-2">
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-tpc-primary/70 hover:text-tpc-primary transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                        Replace image
                        <input type="file" name="featured_image" accept="image/png,image/jpeg,image/webp"
                               @change="handleImage($event)"
                               class="sr-only">
                    </label>
                </div>

                @error('featured_image')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active toggle --}}
            <div class="rounded-2xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5">
                <label class="block text-xs font-bold text-gray-600 mb-3">Visibility</label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="hidden" name="is_active" value="0">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1"
                               id="is_active_create"
                               {{ old('is_active', 1) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-10 h-5.5 rounded-full bg-gray-200 peer-checked:bg-tpc-primary transition-colors duration-200 h-[22px]"></div>
                        <div class="absolute top-0.5 left-0.5 h-[18px] w-[18px] rounded-full bg-white shadow-sm transition-all duration-200 peer-checked:translate-x-[18px]"></div>
                    </div>
                    <span class="text-sm text-gray-700 group-hover:text-gray-900 transition">Active (visible on site)</span>
                </label>
            </div>

            {{-- Submit --}}
            <div class="flex flex-col-reverse sm:flex-row items-center gap-3 pt-2">
                <a href="{{ route('admin.services.index') }}"
                   class="w-full sm:w-auto text-center sm:text-left text-sm font-semibold text-gray-400 hover:text-gray-600 transition py-2">
                    Cancel
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-tpc-primary px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-tpc-secondary transition-all hover:shadow-md active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Create Service
                </button>
            </div>

        </div>
    </form>
@endsection
