{{-- resources/views/admin/program-achievements/edit.blade.php --}}
@extends('admin.layout', ['title' => 'Edit Achievement'])

@section('content')

<div class="mb-5">
  <a href="{{ route('admin.programs.show', $program) }}"
     class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
    ← Back to {{ $program->code }}
  </a>
</div>

<div class="max-w-lg">
  <h2 class="text-base font-bold text-tpc-ink mb-6">Edit Achievement</h2>

  <form method="POST"
        action="{{ route('admin.programs.achievements.update', [$program, $achievement]) }}"
        enctype="multipart/form-data" class="space-y-5">
    @csrf @method('PATCH')

    {{-- Title --}}
    <div>
      <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Title</label>
      <input type="text" name="title" value="{{ old('title', $achievement->title) }}" required
             class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                    focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition">
      @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Year --}}
    <div>
      <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
        Year <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
      </label>
      <input type="text" name="year" value="{{ old('year', $achievement->year) }}"
             class="w-48 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                    focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition">
      @error('year')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Description --}}
    <div>
      <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
        Description <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
      </label>
      <textarea name="description" rows="4"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                       focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition resize-y">{{ old('description', $achievement->description) }}</textarea>
      @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Photo --}}
    <div>
      <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Photo</label>

      {{-- Current photo --}}
      @if ($achievement->photo_path)
        <div id="current-wrap" class="mb-3">
          <p class="text-xs text-tpc-ink/50 mb-2 font-semibold">Current photo:</p>
          <img src="{{ asset('storage/' . $achievement->photo_path) }}"
               class="w-full rounded-xl border border-gray-200 object-contain max-h-80"
               alt="{{ $achievement->title }}">
          <label class="mt-2 inline-flex items-center gap-2 text-sm text-tpc-ink/70 cursor-pointer">
            <input type="checkbox" name="remove_photo" value="1"
                   class="rounded border-gray-300 text-tpc-primary focus:ring-tpc-primary/20">
            Remove current photo
          </label>
        </div>
      @endif

      {{-- New photo preview --}}
      <div id="preview-wrap" style="display:none" class="mb-3">
        <p class="text-xs text-tpc-ink/50 mb-2 font-semibold">New photo:</p>
        <img id="preview-img" src="" alt="New preview"
             class="w-full rounded-xl border border-gray-200 object-contain max-h-80">
        <p class="mt-1 text-xs text-green-600 font-semibold">✓ This will replace the current photo.</p>
      </div>

      <input type="file" name="photo" id="photo-input" accept="image/png,image/jpeg,image/webp"
             class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                    focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition">
      <p class="mt-1 text-xs text-tpc-ink/40">PNG / JPG / WEBP, max 5 MB. The image uploads exactly as-is.</p>
      @error('photo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-3 pt-2">
      <button type="submit"
              class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition">
        Save Changes
      </button>
      <a href="{{ route('admin.programs.show', $program) }}"
         class="text-sm font-semibold text-tpc-ink/50 hover:text-tpc-ink transition">Cancel</a>
    </div>
  </form>
</div>

<script>
document.getElementById('photo-input').addEventListener('change', function(){
  var wrap = document.getElementById('preview-wrap');
  var img  = document.getElementById('preview-img');
  if (!this.files || !this.files[0]) { wrap.style.display='none'; return; }
  img.src = URL.createObjectURL(this.files[0]);
  wrap.style.display = 'block';
});
</script>

@endsection
