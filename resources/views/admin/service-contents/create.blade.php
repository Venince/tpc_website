@extends('admin.layout')

@section('title', 'Add Content Section')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.services.show', $service) }}"
           class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:border-tpc-primary/30 hover:text-tpc-primary transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-base font-bold text-tpc-ink">Add Content Section</h1>
            <p class="text-xs text-tpc-ink/50">{{ $service->title }}</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.services.contents.store', $service) }}" method="POST"
          enctype="multipart/form-data" class="max-w-2xl space-y-6" id="content-section-form">
        @csrf

        {{-- Type selector --}}
        <div>
            <label class="block text-xs font-bold text-gray-600 mb-2">Section Type <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 gap-3">
                <label class="relative cursor-pointer" id="label-text">
                    <input type="radio" name="type" value="text" class="sr-only peer"
                           {{ old('type', 'text') === 'text' ? 'checked' : '' }}>
                    <div class="flex items-center gap-3 rounded-2xl border-2 px-4 py-3 transition
                                border-tpc-primary bg-tpc-primary/5" id="card-text">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-tpc-primary bg-gray-50">
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
                <label class="relative cursor-pointer" id="label-image">
                    <input type="radio" name="type" value="image" class="sr-only peer"
                           {{ old('type') === 'image' ? 'checked' : '' }}>
                    <div class="flex items-center gap-3 rounded-2xl border-2 px-4 py-3 transition
                                border-gray-200" id="card-image">
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

        {{-- Optional heading (always shown) --}}
        <div>
            <label class="block text-xs font-bold text-gray-600 mb-1.5">Section Heading <span class="text-gray-400 font-normal">(optional)</span></label>
            <input type="text" name="heading" value="{{ old('heading') }}"
                   placeholder="e.g. What We Offer"
                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20">
        </div>

        {{-- Text body --}}
        <div id="field-text">
            <label class="block text-xs font-bold text-gray-600 mb-1.5">
                Content <span class="text-red-500">*</span>
            </label>
            <textarea name="body" rows="8" placeholder="Write your content here…"
                      class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20 resize-y @error('body') border-red-300 @enderror">{{ old('body') }}</textarea>
            @error('body')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image upload --}}
        <div id="field-image" class="space-y-3 hidden">
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">
                    Image <span class="text-red-500">*</span>
                </label>
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                       class="block w-full text-sm text-gray-500 file:mr-3 file:rounded-full file:border-0 file:bg-tpc-primary/10 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-tpc-primary hover:file:bg-tpc-primary/20 transition">
                <p class="mt-1 text-[11px] text-gray-400">JPG, PNG or WebP · max 8 MB</p>
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5">Caption <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="text" name="image_caption" value="{{ old('image_caption') }}"
                       placeholder="e.g. Students during lab training"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-tpc-primary focus:outline-none focus:ring-2 focus:ring-tpc-primary/20">
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-3 border-t border-gray-100 pt-5">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-tpc-primary px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-tpc-secondary transition">
                Add Section
            </button>
            <a href="{{ route('admin.services.show', $service) }}"
               class="text-sm font-semibold text-gray-400 hover:text-gray-600 transition">Cancel</a>
        </div>
    </form>

    <script>
    (function () {
        var radios   = document.querySelectorAll('#content-section-form input[name="type"]');
        var fieldText  = document.getElementById('field-text');
        var fieldImage = document.getElementById('field-image');
        var cardText   = document.getElementById('card-text');
        var cardImage  = document.getElementById('card-image');

        function applyType(val) {
            var isImage = val === 'image';

            fieldText.classList.toggle('hidden', isImage);
            fieldImage.classList.toggle('hidden', !isImage);

            cardText.className  = cardText.className
                .replace(/border-tpc-primary|bg-tpc-primary\/5|border-gray-200/g, '').trim();
            cardImage.className = cardImage.className
                .replace(/border-tpc-primary|bg-tpc-primary\/5|border-gray-200/g, '').trim();

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

        // Apply initial state (handles old() repopulation on validation failure)
        var checked = document.querySelector('#content-section-form input[name="type"]:checked');
        applyType(checked ? checked.value : 'text');
    })();
    </script>
@endsection
