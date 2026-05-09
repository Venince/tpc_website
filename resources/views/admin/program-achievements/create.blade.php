{{-- resources/views/admin/program-achievements/create.blade.php --}}
@extends('admin.layout', ['title' => 'Add Achievement'])

@section('content')

<div class="mb-5">
  <a href="{{ route('admin.programs.show', $program) }}"
     class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
    ← Back to {{ $program->code }}
  </a>
</div>

<div class="max-w-lg">
  <h2 class="text-base font-bold text-tpc-ink mb-1">Add Achievement</h2>
  <p class="text-sm text-tpc-ink/50 mb-6">Adding to: <span class="font-semibold text-tpc-ink">{{ $program->name }}</span></p>

  <form method="POST"
        action="{{ route('admin.programs.achievements.store', $program) }}"
        enctype="multipart/form-data" class="space-y-5">
    @csrf

    {{-- Title --}}
    <div>
      <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Title</label>
      <input type="text" name="title" value="{{ old('title') }}" required autofocus
             class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                    focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
             placeholder="e.g. Regional Champion — ICT Olympics">
      @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Year --}}
    <div>
      <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
        Year <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
      </label>
      <input type="text" name="year" value="{{ old('year') }}"
             class="w-48 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                    focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
             placeholder="e.g. 2024 or A.Y. 2023–2024">
      @error('year')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Description --}}
    <div>
      <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
        Description <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
      </label>
      <textarea name="description" rows="4"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm
                       focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition resize-y"
                placeholder="Describe the achievement in detail...">{{ old('description') }}</textarea>
      @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    {{-- Photo — no crop, shows original as-is --}}
    <div>
      <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
        Photo <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
      </label>

      {{-- Live preview --}}
      <div id="preview-wrap" style="display:none" class="mb-3">
        <img id="preview-img" src="" alt="Preview"
             class="w-full rounded-xl border border-gray-200 object-contain max-h-80">
        <p class="mt-1 text-xs text-green-600 font-semibold">✓ Image selected — this is what will be uploaded.</p>
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
        Add Achievement
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
