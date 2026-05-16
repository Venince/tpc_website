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
                         class="w-full max-h-64 rounded-xl border border-tpc-primary/10 object-contain">
                </div>

                <input type="file" name="image" id="image-input" accept="image/png,image/jpeg,image/webp"
                       class="w-full rounded-xl border border-tpc-primary/20 bg-white px-3 py-2 text-sm
                              file:mr-3 file:rounded-lg file:border-0 file:bg-tpc-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-tpc-primary
                              hover:file:bg-tpc-primary/15 transition" />
                <p class="mt-1.5 text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB</p>
                @error('image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Actions --}}
            <div class="pt-2 border-t border-tpc-primary/8 flex flex-wrap gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
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
            const wrap = document.getElementById('preview-wrap');
            const img  = document.getElementById('preview-img');
            if (!this.files || !this.files[0]) { wrap.classList.add('hidden'); return; }
            img.src = URL.createObjectURL(this.files[0]);
            wrap.classList.remove('hidden');
        });
    </script>

@endsection
