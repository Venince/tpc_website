@extends('admin.layout')

@section('title', 'Add Content Section')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.services.show', $service) }}"
           class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-neo-surface shadow-neo-sm text-neo-ink/40 transition hover:shadow-neo-hover active:shadow-neo-inset-sm hover:text-tpc-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-base font-semibold text-tpc-ink">Add Content Section</h1>
            <p class="text-xs text-tpc-ink/50">{{ $service->title }}</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.services.contents.store', $service) }}" method="POST"
          enctype="multipart/form-data" class="max-w-2xl" id="content-section-form">
        @csrf

        <div class="rounded-2xl bg-neo-surface shadow-neo p-5 sm:p-6 space-y-6">

            {{-- Type selector --}}
            <div>
                <label class="block text-xs font-bold text-neo-ink/60 mb-2">Section Type <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    @php $typeIsText = old('type', 'text') === 'text'; @endphp
                    <label class="relative cursor-pointer" id="label-text">
                        <input type="radio" name="type" value="text" class="sr-only peer"
                               {{ $typeIsText ? 'checked' : '' }}>
                        <div class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                    {{ $typeIsText ? 'bg-neo-bg shadow-neo-inset-sm' : 'bg-neo-surface shadow-neo-sm' }}" id="card-text">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg
                                        {{ $typeIsText ? 'bg-neo-surface shadow-neo-sm text-tpc-primary' : 'bg-neo-bg shadow-neo-inset-sm text-neo-ink/40' }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-neo-ink">Text Block</p>
                                <p class="text-xs text-neo-ink/40">Paragraph content</p>
                            </div>
                        </div>
                    </label>
                    <label class="relative cursor-pointer" id="label-image">
                        <input type="radio" name="type" value="image" class="sr-only peer"
                               {{ !$typeIsText ? 'checked' : '' }}>
                        <div class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                    {{ !$typeIsText ? 'bg-neo-bg shadow-neo-inset-sm' : 'bg-neo-surface shadow-neo-sm' }}" id="card-image">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg
                                        {{ !$typeIsText ? 'bg-neo-surface shadow-neo-sm text-tpc-primary' : 'bg-neo-bg shadow-neo-inset-sm text-neo-ink/40' }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-neo-ink">Image Block</p>
                                <p class="text-xs text-neo-ink/40">Upload a photo</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Optional heading (always shown) --}}
            <div>
                <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">Section Heading <span class="text-neo-ink/35 font-normal">(optional)</span></label>
                <input type="text" name="heading" value="{{ old('heading') }}"
                       placeholder="e.g. What We Offer"
                       class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition">
            </div>

            {{-- Text body --}}
            <div id="field-text">
                <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                    Content <span class="text-red-500">*</span>
                </label>

                <input type="hidden" name="body" id="body-input" value="{{ old('body') }}">

                <style>
                    #quill-wrap .ql-toolbar { border: none; border-bottom: 1px solid rgba(0,0,0,0.06); background: transparent; border-radius: 0; }
                    #quill-wrap .ql-container { border: none; background: transparent; font-family: inherit; font-size: 14px; }
                    #quill-wrap .ql-editor { min-height: 180px; padding: 12px 16px; }
                    #quill-wrap .ql-editor.ql-blank::before { font-style: normal; color: rgba(43,54,72,0.35); }
                    #quill-wrap.ql-focused { box-shadow: 0 0 0 2px rgb(0 128 0 / 0.30); }
                    #quill-wrap .ql-snow .ql-picker { color: #2B3648; }
                    #quill-wrap .ql-snow .ql-stroke { stroke: rgba(43,54,72,0.55); }
                    #quill-wrap .ql-snow .ql-fill { fill: rgba(43,54,72,0.55); }
                    #quill-wrap .ql-snow.ql-toolbar button:hover .ql-stroke,
                    #quill-wrap .ql-snow.ql-toolbar button.ql-active .ql-stroke { stroke: var(--color-tpc-primary, #008000); }
                    #quill-wrap .ql-snow.ql-toolbar button:hover .ql-fill,
                    #quill-wrap .ql-snow.ql-toolbar button.ql-active .ql-fill { fill: var(--color-tpc-primary, #008000); }
                    #quill-wrap .ql-snow.ql-toolbar button.ql-active,
                    #quill-wrap .ql-snow.ql-toolbar .ql-picker-label.ql-active { color: var(--color-tpc-primary, #008000); }
                </style>

                <div id="quill-wrap"
                     class="rounded-xl bg-neo-bg shadow-neo-inset-sm overflow-hidden transition @error('body') ring-2 ring-red-300 @enderror"
                     x-data
                     @focusin="$el.classList.add('ql-focused')"
                     @focusout="$el.classList.remove('ql-focused')">
                    <div id="quill-toolbar">
                        <span class="ql-formats">
                            <button class="ql-bold" title="Bold"></button>
                            <button class="ql-italic" title="Italic"></button>
                            <button class="ql-underline" title="Underline"></button>
                        </span>
                        <span class="ql-formats">
                            <button class="ql-list" value="ordered" title="Numbered List"></button>
                            <button class="ql-list" value="bullet" title="Bullet List"></button>
                            <button class="ql-indent" value="-1" title="Decrease Indent"></button>
                            <button class="ql-indent" value="+1" title="Increase Indent"></button>
                        </span>
                        <span class="ql-formats">
                            <select class="ql-align" title="Alignment">
                                <option selected title="Left"></option>
                                <option value="center" title="Center"></option>
                                <option value="right" title="Right"></option>
                                <option value="justify" title="Justify"></option>
                            </select>
                        </span>
                    </div>
                    <div id="quill-editor"></div>
                </div>

                @error('body')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image upload --}}
            <div id="field-image" class="space-y-4 hidden">
                <div>
                    <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                        Image <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-3 rounded-xl bg-neo-bg shadow-neo-inset-sm px-4 py-3">
                        <span class="h-8 w-8 shrink-0 rounded-lg bg-neo-surface shadow-neo-sm flex items-center justify-center">
                            <svg class="h-4 w-4 text-neo-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                        </span>
                        <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                               class="block w-full text-sm text-neo-ink/60 file:mr-3 file:rounded-full file:border-0 file:bg-neo-surface file:shadow-neo-sm file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-tpc-primary hover:file:shadow-neo-hover transition">
                    </div>
                    <p class="mt-1.5 text-[11px] text-neo-ink/40">JPG, PNG or WebP · max 8 MB</p>
                    @error('image')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">Caption <span class="text-neo-ink/35 font-normal">(optional)</span></label>
                    <input type="text" name="image_caption" value="{{ old('image_caption') }}"
                           placeholder="e.g. Students during lab training"
                           class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition">
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 border-t border-black/[0.06] pt-5">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-tpc-primary px-6 py-2.5 text-sm font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                    Add Section
                </button>
                <a href="{{ route('admin.services.show', $service) }}"
                   class="text-sm font-semibold text-neo-ink/40 hover:text-neo-ink transition">Cancel</a>
            </div>
        </div>
    </form>

    <script>
    (function () {
        function initTypeToggle() {
            var textRadio  = document.querySelector('input[name="type"][value="text"]');
            var imageRadio = document.querySelector('input[name="type"][value="image"]');
            var cardText   = document.getElementById('card-text');
            var cardImage  = document.getElementById('card-image');
            var fieldText  = document.getElementById('field-text');
            var fieldImage = document.getElementById('field-image');

            function selectedClasses(active) {
                return active
                    ? ['bg-neo-bg', 'shadow-neo-inset-sm']
                    : ['bg-neo-surface', 'shadow-neo-sm'];
            }
            function badgeClasses(active) {
                return active
                    ? ['bg-neo-surface', 'shadow-neo-sm', 'text-tpc-primary']
                    : ['bg-neo-bg', 'shadow-neo-inset-sm', 'text-neo-ink/40'];
            }

            function applyState() {
                var isText = textRadio.checked;

                cardText.className = 'flex items-center gap-3 rounded-xl px-4 py-3 transition ' + selectedClasses(isText).join(' ');
                cardImage.className = 'flex items-center gap-3 rounded-xl px-4 py-3 transition ' + selectedClasses(!isText).join(' ');

                var textBadge  = cardText.querySelector('span');
                var imageBadge = cardImage.querySelector('span');
                textBadge.className  = 'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg ' + badgeClasses(isText).join(' ');
                imageBadge.className = 'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg ' + badgeClasses(!isText).join(' ');

                fieldText.classList.toggle('hidden', !isText);
                fieldImage.classList.toggle('hidden', isText);
            }

            textRadio.addEventListener('change', applyState);
            imageRadio.addEventListener('change', applyState);
            applyState();
        }

        function initCreateQuill() {
            if (document.getElementById('quill-editor')._quill) return;

            var AlignStyle = Quill.import('attributors/style/align');
            Quill.register(AlignStyle, true);

            var editor = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Write your content here…',
                modules: {
                    toolbar: '#quill-toolbar',
                },
            });

            editor.root._quill = editor; // mark as initialized

            // Pre-fill on validation failure
            var old = document.getElementById('body-input').value;
            if (old) editor.root.innerHTML = old;

            // Copy HTML to hidden input on submit
            var form = document.getElementById('content-section-form');
            form.addEventListener('submit', function () {
                var html = editor.root.innerHTML;
                document.getElementById('body-input').value =
                    (html === '<p><br></p>' || html.trim() === '') ? '' : html;
            });
        }

        function loadQuillThen(cb) {
            if (typeof Quill !== 'undefined') { cb(); return; }

            var link = document.createElement('link');
            link.rel  = 'stylesheet';
            link.href = 'https://cdn.quilljs.com/1.3.7/quill.snow.css';
            document.head.appendChild(link);

            var script  = document.createElement('script');
            script.src  = 'https://cdn.quilljs.com/1.3.7/quill.min.js';
            script.onload = cb;
            document.head.appendChild(script);
        }

        initTypeToggle();
        loadQuillThen(initCreateQuill);
    })();
    </script>
@endsection
