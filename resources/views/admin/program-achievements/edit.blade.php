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

    <div class="max-w-lg space-y-5">

        {{-- ── Main form ── --}}
        <form method="POST"
              action="{{ route('admin.programs.achievements.update', [$program, $achievement]) }}"
              enctype="multipart/form-data"
              class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6 space-y-5">
            @csrf @method('PATCH')

            {{-- Title --}}
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $achievement->title) }}" required
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Year --}}
            <div>
                <label for="year" class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Year <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <input type="text" name="year" id="year" value="{{ old('year', $achievement->year) }}"
                       class="w-48 rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('year') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Description <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <textarea name="description" id="description" rows="4"
                          class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition resize-none">{{ old('description', $achievement->description) }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- ── Existing images ── --}}
            @php $images = $achievement->images; @endphp
            @if ($images->isNotEmpty())
                <div>
                    <p class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-2">
                        Current Photos
                        <span class="normal-case font-normal text-tpc-ink/40">({{ $images->count() }})</span>
                    </p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ($images as $image)
                            <div class="relative rounded-xl overflow-hidden border border-tpc-primary/15 bg-gray-50 aspect-square group">
                                <img src="{{ asset('storage/' . $image->path) }}"
                                     class="w-full h-full object-cover"
                                     alt="">
                                {{-- Delete button (separate mini-form) --}}
                                <form method="POST"
                                      action="{{ route('admin.programs.achievements.images.destroy', [$program, $achievement, $image]) }}"
                                      class="absolute top-1 right-1"
                                      onsubmit="return confirm('Remove this photo?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="rounded-full bg-black/60 text-white w-6 h-6 flex items-center justify-center hover:bg-red-600 transition text-xs opacity-0 group-hover:opacity-100">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Add more photos ── --}}
            <div>
                <label for="photos-input" class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Add More Photos
                    <span class="normal-case font-normal text-tpc-ink/40">(optional · up to 10 at a time)</span>
                </label>

                <div id="drop-zone"
                     class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-tpc-primary/25 bg-tpc-primary/3 px-4 py-7 text-center cursor-pointer hover:border-tpc-primary/50 hover:bg-tpc-primary/5 transition">
                    <svg class="h-7 w-7 text-tpc-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <p class="text-sm font-semibold text-tpc-primary/70">Click to add or drag &amp; drop</p>
                    <p class="text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB each</p>
                    <input type="file" name="photos[]" id="photos-input" accept="image/png,image/jpeg,image/webp"
                           multiple class="absolute inset-0 opacity-0 cursor-pointer" />
                </div>

                @error('photos')   <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                @error('photos.*') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror

                <div id="preview-grid" class="hidden mt-3 grid grid-cols-3 gap-2"></div>
                <p id="preview-count" class="hidden mt-1.5 text-xs font-semibold text-tpc-primary"></p>
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
    (function () {
        const input    = document.getElementById('photos-input');
        const dropZone = document.getElementById('drop-zone');
        const grid     = document.getElementById('preview-grid');
        const countEl  = document.getElementById('preview-count');
        const MAX      = 10;

        let selectedFiles = [];

        function renderPreviews() {
            grid.innerHTML = '';
            if (selectedFiles.length === 0) {
                grid.classList.add('hidden');
                countEl.classList.add('hidden');
                return;
            }
            grid.classList.remove('hidden');
            countEl.classList.remove('hidden');
            countEl.textContent = selectedFiles.length + ' new image' + (selectedFiles.length > 1 ? 's' : '') + ' queued';

            selectedFiles.forEach((file, idx) => {
                const wrap = document.createElement('div');
                wrap.className = 'relative rounded-xl overflow-hidden border border-tpc-primary/15 bg-gray-50 aspect-square';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'w-full h-full object-cover';
                wrap.appendChild(img);

                // New badge
                const badge = document.createElement('span');
                badge.className = 'absolute bottom-1 left-1 rounded-full bg-tpc-primary/80 text-white text-[9px] font-bold px-1.5 py-0.5';
                badge.textContent = 'NEW';
                wrap.appendChild(badge);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'absolute top-1 right-1 rounded-full bg-black/60 text-white w-5 h-5 flex items-center justify-center hover:bg-red-600 transition text-xs leading-none';
                btn.innerHTML = '&times;';
                btn.addEventListener('click', () => {
                    selectedFiles.splice(idx, 1);
                    syncInput();
                    renderPreviews();
                });
                wrap.appendChild(btn);

                grid.appendChild(wrap);
            });
        }

        function syncInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            input.files = dt.files;
        }

        function addFiles(newFiles) {
            const combined = [...selectedFiles, ...Array.from(newFiles)];
            selectedFiles  = combined.slice(0, MAX);
            syncInput();
            renderPreviews();
        }

        input.addEventListener('change', () => addFiles(input.files));

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-tpc-primary/60', 'bg-tpc-primary/8');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-tpc-primary/60', 'bg-tpc-primary/8');
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-tpc-primary/60', 'bg-tpc-primary/8');
            addFiles(e.dataTransfer.files);
        });
    })();
    </script>

@endsection
