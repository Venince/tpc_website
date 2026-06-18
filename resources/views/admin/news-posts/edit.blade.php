@extends('admin.layout', ['title' => 'Edit Post'])

@section('content')

    {{-- ── Page header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <a href="{{ route('admin.news-posts.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition mb-2">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                News Posts
            </a>
            <h1 class="text-lg font-bold text-tpc-ink">Edit News Post</h1>
        </div>
        <div class="self-start sm:self-auto">
            @include('admin.news-posts._status-badge', ['post' => $newsPost])
        </div>
    </div>

    {{-- Status banner --}}
    @if($newsPost->isDeclined())
        <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3">
            <svg class="h-4 w-4 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs text-red-800">
                <span class="font-semibold">Declined.</span>
                {{ $newsPost->review_note ? ' Reason: ' . $newsPost->review_note : ' No reason provided.' }}
                <span class="block mt-0.5 text-red-700/80">Edit and save to re-submit for review.</span>
            </div>
        </div>
    @elseif($newsPost->isPending())
        <div class="mb-5 flex items-start gap-3 rounded-2xl border border-yellow-200 bg-yellow-50 px-4 py-3">
            <svg class="h-4 w-4 text-yellow-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-yellow-800">
                <span class="font-semibold">Pending review.</span>
                Saving will re-submit this post for superadmin approval.
            </p>
        </div>
    @elseif($newsPost->isApproved())
        <div class="mb-5 flex items-start gap-3 rounded-2xl border border-tpc-primary/20 bg-tpc-primary/5 px-4 py-3">
            <svg class="h-4 w-4 text-tpc-primary mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-xs text-tpc-ink/80">
                <span class="font-semibold text-tpc-primary">Currently approved & live.</span>
                Saving changes will un-publish this post and re-submit it for review.
            </p>
        </div>
    @endif

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.news-posts.update', $newsPost) }}" enctype="multipart/form-data"
              class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6 space-y-5">
            @csrf @method('PUT')

            {{-- Title --}}
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $newsPost->title) }}" required
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Category + Excerpt row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="category" class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Category</label>
                    <div class="relative">
                        <select name="category" id="category"
                                class="w-full appearance-none rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition bg-white pr-9">
                            @foreach (['Announcement','Event','Advisory','Scholarship'] as $cat)
                                <option value="{{ $cat }}" @selected(old('category', $newsPost->category) === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-tpc-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    @error('category') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="excerpt" class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                        Excerpt <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                    </label>
                    <input type="text" name="excerpt" id="excerpt" value="{{ old('excerpt', $newsPost->excerpt) }}"
                           class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                    @error('excerpt') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Body --}}
            <div>
                <label for="body" class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Body</label>
                <textarea name="body" id="body" rows="10" required
                          class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition resize-none">{{ old('body', $newsPost->body) }}</textarea>
                @error('body') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- ── Existing photos ── --}}
            @php $images = $newsPost->galleryImages; @endphp
            @if ($images->isNotEmpty())
                <div>
                    <p class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-2">
                        Current Photos
                        <span class="normal-case font-normal text-tpc-ink/40">({{ $images->count() }})</span>
                    </p>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        @foreach ($images as $image)
                            <div class="relative rounded-xl overflow-hidden border border-tpc-primary/15 bg-gray-50 aspect-square group">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     class="w-full h-full object-cover"
                                     alt="">
                                {{-- ✅ Plain button — no nested <form> --}}
                                <button type="button"
                                        data-delete-url="{{ route('admin.news-posts.gallery.destroy', [$newsPost, $image]) }}"
                                        class="delete-photo-btn absolute top-1 right-1 rounded-full bg-black/60 text-white w-6 h-6 flex items-center justify-center hover:bg-red-600 transition text-xs opacity-0 group-hover:opacity-100">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Add more photos ── --}}
            <div>
                <label for="photos-input" class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Add More Photos
                    <span class="normal-case font-normal text-tpc-ink/40">(optional · up to 20 at a time)</span>
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

                <div id="preview-grid" class="hidden mt-3 grid grid-cols-3 sm:grid-cols-4 gap-2"></div>
                <p id="preview-count" class="hidden mt-1.5 text-xs font-semibold text-tpc-primary"></p>
            </div>

            {{-- Actions --}}
            <div class="pt-2 border-t border-tpc-primary/8 flex flex-wrap gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save & Re-submit
                </button>
                <a href="{{ route('admin.news-posts.index') }}"
                   class="inline-flex items-center rounded-xl border border-tpc-primary/25 bg-white px-5 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- ✅ Single delete form, OUTSIDE the main form --}}
    <form id="delete-photo-form" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
    (function () {
        // ── Photo delete wiring ──────────────────────────────────────────
        const deleteForm = document.getElementById('delete-photo-form');
        document.querySelectorAll('.delete-photo-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (!confirm('Remove this photo?')) return;
                deleteForm.action = btn.dataset.deleteUrl;
                deleteForm.submit();
            });
        });

        // ── New photo upload preview ─────────────────────────────────────
        const input    = document.getElementById('photos-input');
        const dropZone = document.getElementById('drop-zone');
        const grid     = document.getElementById('preview-grid');
        const countEl  = document.getElementById('preview-count');
        const MAX      = 20;

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
