@extends('admin.layout', ['title' => 'Edit Achievement'])

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <a href="{{ route('admin.programs.show', $program) }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition mb-2">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $program->code }}
            </a>
            <h1 class="text-lg font-bold text-tpc-ink">Edit Achievement</h1>
        </div>
    </div>

    <div class="max-w-lg">
        <form method="POST"
              action="{{ route('admin.programs.achievements.update', [$program, $achievement]) }}"
              enctype="multipart/form-data"
              class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6 space-y-5">
            @csrf @method('PATCH')

            {{-- Title --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Title</label>
                <input type="text" name="title" value="{{ old('title', $achievement->title) }}" required
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Year --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Year <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <input type="text" name="year" value="{{ old('year', $achievement->year) }}"
                       class="w-48 rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('year') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Description <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <textarea name="description" rows="4"
                          class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition resize-none">{{ old('description', $achievement->description) }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Photo --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Photo</label>

                @if ($achievement->photo_path)
                    <div class="mb-3 rounded-xl border border-tpc-primary/12 bg-tpc-primary/3 p-3">
                        <p class="text-xs font-semibold text-tpc-ink/60 mb-2">Current photo</p>
                        <img src="{{ asset('storage/' . $achievement->photo_path) }}"
                             class="w-full max-h-64 rounded-xl border border-tpc-primary/10 object-contain"
                             alt="{{ $achievement->title }}">
                        <label class="mt-2.5 inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remove_photo" value="1"
                                   class="rounded border-red-300 text-red-500 focus:ring-red-300/30 w-3.5 h-3.5">
                            <span class="text-xs text-red-600 font-medium">Remove current photo</span>
                        </label>
                    </div>
                @endif

                <div id="preview-wrap" class="hidden mb-3 rounded-xl border border-tpc-primary/12 bg-tpc-primary/3 p-3">
                    <p class="text-xs font-semibold text-tpc-ink/60 mb-2">New photo preview</p>
                    <img id="preview-img" src="" alt="Preview"
                         class="w-full max-h-64 rounded-xl border border-tpc-primary/10 object-contain">
                    <p class="mt-2 text-xs font-semibold text-green-600 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        This will replace the current photo.
                    </p>
                </div>

                <input type="file" name="photo" id="photo-input" accept="image/png,image/jpeg,image/webp"
                       class="w-full rounded-xl border border-tpc-primary/20 bg-white px-3 py-2 text-sm
                              file:mr-3 file:rounded-lg file:border-0 file:bg-tpc-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-tpc-primary
                              hover:file:bg-tpc-primary/15 transition" />
                <p class="mt-1.5 text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB</p>
                @error('photo') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-2 border-t border-tpc-primary/8 flex flex-wrap gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('admin.programs.show', $program) }}"
                   class="inline-flex items-center rounded-xl border border-tpc-primary/25 bg-white px-5 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('photo-input').addEventListener('change', function () {
        const wrap = document.getElementById('preview-wrap');
        const img  = document.getElementById('preview-img');
        if (!this.files || !this.files[0]) { wrap.classList.add('hidden'); return; }
        img.src = URL.createObjectURL(this.files[0]);
        wrap.classList.remove('hidden');
    });
    </script>

@endsection
