@extends('admin.layout', ['title' => 'New Post'])

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
            <h1 class="text-lg font-bold text-tpc-ink">Create News Post</h1>
        </div>
    </div>

    {{-- Pending note --}}
    @if(!auth()->user()->is_super_admin)
        <div class="mb-5 flex items-start gap-3 rounded-2xl border border-yellow-200 bg-yellow-50 px-4 py-3">
            <svg class="h-4 w-4 text-yellow-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-xs text-yellow-800">
                <span class="font-semibold">Heads up:</span>
                After saving, this post will be sent to the superadmin for review before it goes live.
            </p>
        </div>
    @endif

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.news-posts.store') }}" enctype="multipart/form-data"
              class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6 space-y-5">
            @csrf

            {{-- Title --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="Enter post title…"
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition placeholder:text-tpc-ink/30" />
                @error('title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Category + Excerpt row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Category</label>
                    <div class="relative">
                        <select name="category"
                                class="w-full appearance-none rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition bg-white pr-9">
                            @foreach (['Announcement','Event','Advisory','Scholarship'] as $cat)
                                <option value="{{ $cat }}" @selected(old('category','Announcement') === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-tpc-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    @error('category') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                        Excerpt <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                    </label>
                    <input type="text" name="excerpt" value="{{ old('excerpt') }}"
                           placeholder="Short summary…"
                           class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition placeholder:text-tpc-ink/30" />
                    @error('excerpt') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Body --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Body</label>
                <textarea name="body" rows="10" required
                          placeholder="Write your post content here…"
                          class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition resize-none placeholder:text-tpc-ink/30">{{ old('body') }}</textarea>
                @error('body') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Image --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Post Image <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>

                <div id="preview-wrap" class="hidden mb-3 rounded-xl border border-tpc-primary/12 bg-tpc-primary/3 p-3">
                    <p class="text-xs font-semibold text-tpc-ink/60 mb-2">Preview</p>
                    <img id="preview-img" src="" alt="Preview"
                        class="w-full rounded-xl border border-tpc-primary/10 object-contain">
                    <p id="preview-meta" class="mt-1.5 text-xs text-tpc-ink/40"></p>
                </div>

                <input type="file" name="image" id="image-input" accept="image/png,image/jpeg,image/webp"
                    class="w-full rounded-xl border border-tpc-primary/20 bg-white px-3 py-2 text-sm
                            file:mr-3 file:rounded-lg file:border-0 file:bg-tpc-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-tpc-primary
                            hover:file:bg-tpc-primary/15 transition @error('image') border-red-400 @enderror" />
                <p class="mt-1.5 text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB · 100×100 – 4000×4000 px</p>
                <p id="image-error" class="mt-1 text-xs text-red-600 hidden"></p>
                @error('image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Gallery Images --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Photo Gallery
                    <span class="normal-case font-normal text-tpc-ink/40">(optional · up to 20 photos)</span>
                </label>

                {{-- Selected previews --}}
                <div id="gallery-preview-grid"
                     class="hidden grid grid-cols-3 sm:grid-cols-4 gap-2 mb-3"></div>

                <label for="gallery-input"
                       class="inline-flex items-center gap-2 cursor-pointer rounded-xl border-2 border-dashed
                              border-tpc-primary/30 bg-tpc-primary/3 px-4 py-2.5 text-sm font-semibold
                              text-tpc-primary hover:border-tpc-primary/60 hover:bg-tpc-primary/8 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span id="gallery-btn-label">Add Photos</span>
                </label>
                <input type="file" name="gallery_images[]" id="gallery-input"
                       multiple accept="image/png,image/jpeg,image/webp" class="sr-only">
                <p class="mt-1.5 text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB each</p>
            </div>

            {{-- Actions --}}
            <div class="pt-2 border-t border-tpc-primary/8 flex flex-wrap gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Submit for Review
                </button>
                <a href="{{ route('admin.news-posts.index') }}"
                class="inline-flex items-center rounded-xl border border-tpc-primary/25 bg-white px-5 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                    Cancel
                </a>
            </div>
            </form>
            </div>

            <script>
                document.getElementById('image-input').addEventListener('change', function () {
                    const wrap       = document.getElementById('preview-wrap');
                    const previewImg = document.getElementById('preview-img');
                    const meta       = document.getElementById('preview-meta');
                    const errorEl    = document.getElementById('image-error');

                    // Reset
                    wrap.classList.add('hidden');
                    errorEl.classList.add('hidden');
                    errorEl.textContent = '';
                    this.setCustomValidity('');

                    const file = this.files?.[0];
                    if (!file) return;

                    const MAX_BYTES = 5 * 1024 * 1024;
                    const MIN_PX    = 100;
                    const MAX_PX    = 4000;

                    if (file.size > MAX_BYTES) {
                        const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                        errorEl.textContent = `File is too large (${sizeMB} MB). Maximum allowed size is 5 MB.`;
                        errorEl.classList.remove('hidden');
                        this.setCustomValidity('File too large.');
                        return;
                    }

                    const objectUrl = URL.createObjectURL(file);
                    const img = new Image();
                    img.onload = () => {
                        const w = img.naturalWidth;
                        const h = img.naturalHeight;
                        URL.revokeObjectURL(objectUrl);

                        const errors = [];
                        if (w < MIN_PX || h < MIN_PX)
                            errors.push(`Too small (${w}×${h} px). Minimum is ${MIN_PX}×${MIN_PX} px.`);
                        if (w > MAX_PX || h > MAX_PX)
                            errors.push(`Too large (${w}×${h} px). Maximum is ${MAX_PX}×${MAX_PX} px.`);

                        if (errors.length) {
                            errorEl.textContent = errors.join(' ');
                            errorEl.classList.remove('hidden');
                            this.setCustomValidity(errors.join(' '));
                            return;
                        }

                        this.setCustomValidity('');
                        previewImg.src = URL.createObjectURL(file);
                        meta.textContent = `${w}×${h} px · ${(file.size / 1024).toFixed(0)} KB`;
                        wrap.classList.remove('hidden');
                    };
                    img.src = objectUrl;
                });

                (function () {
                    const input    = document.getElementById('gallery-input');
                    const grid     = document.getElementById('gallery-preview-grid');
                    const btnLabel = document.getElementById('gallery-btn-label');
                    let fileList   = new DataTransfer();

                    input.addEventListener('change', function () {
                        Array.from(this.files).forEach(file => {
                            // Skip duplicates by name+size
                            const exists = Array.from(fileList.files)
                                .some(f => f.name === file.name && f.size === file.size);
                            if (!exists) fileList.items.add(file);
                        });
                        this.files = fileList.files;
                        renderPreviews();
                    });

                    function renderPreviews() {
                        grid.innerHTML = '';
                        const files = Array.from(fileList.files);
                        if (!files.length) {
                            grid.classList.add('hidden');
                            btnLabel.textContent = 'Add Photos';
                            return;
                        }
                        grid.classList.remove('hidden');
                        btnLabel.textContent = `Add More (${files.length} selected)`;

                        files.forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const wrap = document.createElement('div');
                                wrap.className = 'relative group rounded-xl overflow-hidden border border-tpc-primary/15 aspect-square bg-gray-100';
                                wrap.innerHTML = `
                                    <img src="${e.target.result}" class="w-full h-full object-cover" alt="">
                                    <button type="button" data-index="${index}"
                                            class="remove-gallery-btn absolute top-1 right-1 h-5 w-5 rounded-full
                                                bg-black/60 text-white flex items-center justify-center
                                                opacity-0 group-hover:opacity-100 transition text-xs font-bold">
                                        ×
                                    </button>`;
                                wrap.querySelector('.remove-gallery-btn').addEventListener('click', function () {
                                    removeFile(parseInt(this.dataset.index));
                                });
                                grid.appendChild(wrap);
                            };
                            reader.readAsDataURL(file);
                        });
                    }

                    function removeFile(index) {
                        const dt = new DataTransfer();
                        Array.from(fileList.files).forEach((f, i) => { if (i !== index) dt.items.add(f); });
                        fileList = dt;
                        input.files = fileList.files;
                        renderPreviews();
                    }
                })();
            </script>

@endsection
