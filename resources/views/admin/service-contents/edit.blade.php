@extends('admin.layout')

@section('title', 'Edit Content Section')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.services.show', $service) }}"
           class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:border-tpc-primary/30 hover:text-tpc-primary transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-base font-bold text-tpc-ink">Edit Content Section</h1>
            <p class="text-xs text-tpc-ink/50">{{ $service->title }}</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.services.contents.update', [$service, $content]) }}" method="POST"
          enctype="multipart/form-data" class="max-w-2xl space-y-6" id="edit-content-section-form">
        @csrf @method('PATCH')

        {{-- Type selector --}}
        <div>
            <label class="block text-xs font-bold text-gray-600 mb-2">Section Type</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="relative cursor-pointer">
                    <input type="radio" name="type" value="text" class="sr-only peer"
                           {{ old('type', $content->type) === 'text' ? 'checked' : '' }}>
                    <div class="flex items-center gap-3 rounded-2xl border-2 px-4 py-3 transition
                                {{ old('type', $content->type) === 'text' ? 'border-tpc-primary bg-tpc-primary/5' : 'border-gray-200' }}"
                         id="edit-card-text">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-gray-50">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Text Block</p>
                            <p class="text-xs text-gray-400">Paragraph content</p>
                        </div>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" name="type" value="image" class="sr-only peer"
                           {{ old('type', $content->type) === 'image' ? 'checked' : '' }}>
                    <div class="flex items-center gap-3 rounded-2xl border-2 px-4 py-3 transition
                                {{ old('type', $content->type) === 'image' ? 'border-tpc-primary bg-tpc-primary/5' : 'border-gray-200' }}"
                         id="edit-card-image">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-gray-50">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Image Block</p>
                            <p class="text-xs text-gray-400">Upload a photo</p>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Heading --}}
        <div>
            <label class="block text-xs font-bold text-gray-600 mb-1.5">Section Heading <span class="text-gray-400 font-normal">(optional)</span></label>
            <input type="text" name="heading" value="{{ old('heading', $content->heading) }}"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20">
        </div>

        {{-- Text body --}}
        <div id="edit-field-text" class="{{ old('type', $content->type) === 'image' ? 'hidden' : '' }}">
            <label class="block text-xs font-bold text-gray-600 mb-1.5">Content</label>
            <textarea name="body" rows="8"
                      class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 resize-y">{{ old('body', $content->body) }}</textarea>
        </div>

        {{-- Image --}}
        <div id="edit-field-image" class="space-y-3 {{ old('type', $content->type) === 'text' ? 'hidden' : '' }}">
            @if ($content->image_path)
                <div>
                    <p class="text-xs font-bold text-gray-600 mb-2">Current Image</p>
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $content->image_path) }}"
                             class="h-28 w-48 rounded-xl object-cover border border-gray-200" alt="">
                        <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-red-500">
                            Remove this image
                        </label>
                    </div>
                </div>
            @endif
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">
                    {{ $content->image_path ? 'Replace Image' : 'Upload Image' }}
                    @if (!$content->image_path)<span class="text-red-500">*</span>@endif
                </label>
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                       class="block w-full text-sm text-gray-500 file:mr-3 file:rounded-full file:border-0 file:bg-tpc-primary/10 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-tpc-primary hover:file:bg-tpc-primary/20 transition">
                <p class="mt-1 text-[11px] text-gray-400">JPG, PNG or WebP · max 8 MB</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Caption</label>
                <input type="text" name="image_caption" value="{{ old('image_caption', $content->image_caption) }}"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20">
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3 border-t border-gray-100 pt-5">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-tpc-primary px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-tpc-secondary transition">
                Save Changes
            </button>
            <a href="{{ route('admin.services.show', $service) }}"
               class="text-sm font-semibold text-gray-400 hover:text-gray-600 transition">Cancel</a>
        </div>
    </form>

    <script>
    (function () {
        var radios     = document.querySelectorAll('#edit-content-section-form input[name="type"]');
        var fieldText  = document.getElementById('edit-field-text');
        var fieldImage = document.getElementById('edit-field-image');
        var cardText   = document.getElementById('edit-card-text');
        var cardImage  = document.getElementById('edit-card-image');

        function applyType(val) {
            var isImage = val === 'image';

            fieldText.classList.toggle('hidden', isImage);
            fieldImage.classList.toggle('hidden', !isImage);

            cardText.classList.remove('border-tpc-primary', 'bg-tpc-primary/5', 'border-gray-200');
            cardImage.classList.remove('border-tpc-primary', 'bg-tpc-primary/5', 'border-gray-200');

            if (isImage) {
                cardImage.classList.add('border-tpc-primary', 'bg-tpc-primary/5');
                cardText.classList.add('border-gray-200');
            } else {
                cardText.classList.add('border-tpc-primary', 'bg-tpc-primary/5');
                cardImage.classList.add('border-gray-200');
            }
        }

        radios.forEach(function (r) {
            r.addEventListener('change', function () { applyType(this.value); });
        });

        var checked = document.querySelector('#edit-content-section-form input[name="type"]:checked');
        applyType(checked ? checked.value : 'text');
    })();
    </script>
@endsection
