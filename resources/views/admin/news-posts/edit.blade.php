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
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Title</label>
                <input type="text" name="title" value="{{ old('title', $newsPost->title) }}" required
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
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
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                        Excerpt <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                    </label>
                    <input type="text" name="excerpt" value="{{ old('excerpt', $newsPost->excerpt) }}"
                           class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                    @error('excerpt') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Body --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Body</label>
                <textarea name="body" rows="10" required
                          class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition resize-none">{{ old('body', $newsPost->body) }}</textarea>
                @error('body') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Image --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Post Image</label>

                @if($newsPost->image_path)
                    <div class="mb-3 rounded-xl border border-tpc-primary/12 bg-tpc-primary/3 p-3">
                        <p class="text-xs font-semibold text-tpc-ink/60 mb-2">Current image</p>
                        <img src="{{ asset('storage/' . $newsPost->image_path) }}"
                            class="w-full max-h-64 rounded-xl border border-tpc-primary/10 object-contain"
                            alt="{{ $newsPost->title }}">
                        <label class="mt-2.5 inline-flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remove_image" value="1"
                                class="rounded border-red-300 text-red-500 focus:ring-red-300/30 w-3.5 h-3.5">
                            <span class="text-xs text-red-600 font-medium">Remove current image</span>
                        </label>
                    </div>
                @endif

                <div id="preview-wrap" class="hidden mb-3 rounded-xl border border-tpc-primary/12 bg-tpc-primary/3 p-3">
                    <p class="text-xs font-semibold text-tpc-ink/60 mb-2">New image preview</p>
                    <img id="preview-img" src="" alt="Preview"
                        class="w-full max-h-64 rounded-xl border border-tpc-primary/10 object-contain">
                    <p id="preview-meta" class="mt-1.5 text-xs text-tpc-ink/40"></p>
                    <p class="mt-1 text-xs font-semibold text-green-600 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        This will replace the current image.
                    </p>
                </div>

                <input type="file" name="image" id="image-input" accept="image/png,image/jpeg,image/webp"
                    class="w-full rounded-xl border border-tpc-primary/20 bg-white px-3 py-2 text-sm
                            file:mr-3 file:rounded-lg file:border-0 file:bg-tpc-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-tpc-primary
                            hover:file:bg-tpc-primary/15 transition @error('image') border-red-400 @enderror" />
                <p class="mt-1.5 text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB · 100×100 – 4000×4000 px</p>
                <p id="image-error" class="mt-1 text-xs text-red-600 hidden"></p>
                @error('image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
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
            </script>

@endsection
