@extends('admin.layout', ['title' => 'New Post'])

@section('content')
    <div class="flex items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-tpc-ink">Create News Post</h1>
            <p class="mt-1 text-sm text-tpc-ink/70">Add a new announcement or update.</p>
        </div>
        <a href="{{ route('admin.news-posts.index') }}" class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">
            ← Back
        </a>
    </div>

    <form
        class="mt-6 rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm"
        method="POST"
        action="{{ route('admin.news-posts.store') }}"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Title</label>
                <input name="title" value="{{ old('title') }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
                @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-tpc-ink">Category</label>
                <select name="category"
                        class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20">
                    @foreach (['Announcement','Event','Advisory','Scholarship'] as $cat)
                        <option value="{{ $cat }}" @selected(old('category','Announcement') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-tpc-ink">Excerpt (optional)</label>
                <input name="excerpt" value="{{ old('excerpt') }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
                @error('excerpt') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Body</label>
                <textarea name="body" rows="9"
                          class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20">{{ old('body') }}</textarea>
                @error('body') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Post Image (optional)</label>
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 bg-white px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
                <p class="mt-2 text-xs text-tpc-ink/60">PNG/JPG/WEBP up to 5MB.</p>
                @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <input id="is_published" type="checkbox" name="is_published" value="1"
                       {{ old('is_published') ? 'checked' : '' }}
                       class="rounded border-tpc-primary/30 text-tpc-primary focus:ring-tpc-primary/20" />
                <label for="is_published" class="text-sm text-tpc-ink/80">Publish now</label>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="rounded-lg bg-green-600 px-5 py-3 text-sm font-medium text-white hover:bg-green-700">
                Save
            </button>
            <a href="{{ route('admin.news-posts.index') }}"
               class="rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                Cancel
            </a>
        </div>
    </form>
@endsection
