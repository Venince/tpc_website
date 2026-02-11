@extends('admin.layout', ['title' => 'Edit Post'])

@section('content')
    <div class="flex items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-tpc-ink">Edit News Post</h1>
            <p class="mt-1 text-sm text-tpc-ink/70">Update post details.</p>
        </div>
        <a href="{{ route('admin.news-posts.index') }}" class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary">
            ← Back
        </a>
    </div>

    <form
        class="mt-6 rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm"
        method="POST"
        action="{{ route('admin.news-posts.update', $newsPost) }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Title</label>
                <input name="title" value="{{ old('title', $newsPost->title) }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
                @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-tpc-ink">Category</label>
                <select name="category"
                        class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20">
                    @foreach (['Announcement','Event','Advisory','Scholarship'] as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $newsPost->category) === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-tpc-ink">Excerpt (optional)</label>
                <input name="excerpt" value="{{ old('excerpt', $newsPost->excerpt) }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
                @error('excerpt') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Body</label>
                <textarea name="body" rows="9"
                          class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20">{{ old('body', $newsPost->body) }}</textarea>
                @error('body') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Post Image</label>

                @if($newsPost->image_path)
                    <div class="mt-2 flex items-center gap-4">
                        <img
                            src="{{ asset('storage/' . $newsPost->image_path) }}"
                            class="h-16 w-28 rounded-xl object-cover"
                            alt="Post image"
                        />
                        <label class="inline-flex items-center gap-2 text-sm text-tpc-ink/80">
                            <input type="checkbox" name="remove_image" value="1"
                                   class="rounded border-tpc-primary/30 text-tpc-primary focus:ring-tpc-primary/20">
                            Remove current image
                        </label>
                    </div>
                @else
                    <p class="mt-2 text-sm text-tpc-ink/60">No image uploaded yet.</p>
                @endif

                <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                       class="mt-3 w-full rounded-lg border border-tpc-primary/20 bg-white px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
                <p class="mt-2 text-xs text-tpc-ink/60">Upload a new file to replace the current image. (PNG/JPG/WEBP up to 5MB)</p>
                @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <input id="is_published" type="checkbox" name="is_published" value="1"
                       {{ old('is_published', $newsPost->is_published) ? 'checked' : '' }}
                       class="rounded border-tpc-primary/30 text-tpc-primary focus:ring-tpc-primary/20" />
                <label for="is_published" class="text-sm text-tpc-ink/80">Published</label>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="rounded-lg bg-green-600 px-5 py-3 text-sm font-medium text-white hover:bg-green-700">
                Update
            </button>
            <a href="{{ route('admin.news-posts.index') }}"
               class="rounded-lg border border-tpc-primary/30 bg-white px-5 py-3 text-sm font-medium text-tpc-primary hover:bg-tpc-primary/5">
                Cancel
            </a>
        </div>
    </form>
@endsection
