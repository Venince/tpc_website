@extends('admin.layout')

@section('title', 'Edit Content Section')

@section('page_actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.services.show', $service) }}"
           class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-neo-surface shadow-neo-sm text-neo-ink/40 transition hover:shadow-neo-hover active:shadow-neo-inset-sm hover:text-tpc-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-base font-semibold text-tpc-ink">Edit Content Section</h1>
            <p class="text-xs text-tpc-ink/50">{{ $service->title }}</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('admin.services.contents.update', [$service, $content]) }}" method="POST"
          enctype="multipart/form-data" class="max-w-2xl" id="edit-content-section-form">
        @csrf @method('PATCH')

        <div class="rounded-2xl bg-neo-surface shadow-neo p-5 sm:p-6 space-y-6">

            {{-- Type selector --}}
            <div>
                <label class="block text-xs font-bold text-neo-ink/60 mb-2">Section Type</label>
                <div class="grid grid-cols-2 gap-3">
                    @php $typeIsText = old('type', $content->type) === 'text'; @endphp
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="text" class="sr-only peer"
                               {{ $typeIsText ? 'checked' : '' }}>
                        <div class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                    {{ $typeIsText ? 'bg-neo-bg shadow-neo-inset-sm' : 'bg-neo-surface shadow-neo-sm' }}"
                             id="edit-card-text">
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
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="image" class="sr-only peer"
                               {{ !$typeIsText ? 'checked' : '' }}>
                        <div class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                                    {{ !$typeIsText ? 'bg-neo-bg shadow-neo-inset-sm' : 'bg-neo-surface shadow-neo-sm' }}"
                             id="edit-card-image">
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

            {{-- Heading --}}
            <div>
                <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">Section Heading <span class="text-neo-ink/35 font-normal">(optional)</span></label>
                <input type="text" name="heading" value="{{ old('heading', $content->heading) }}"
                       class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition">
            </div>

            {{-- Text body --}}
            <div id="edit-field-text" class="{{ $typeIsText ? '' : 'hidden' }}">
                <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">Content</label>

                <input type="hidden" name="body" id="edit-body-input">

                <style>
                    #edit-quill-wrap .ql-toolbar { border: none; border-bottom: 1px solid rgba(0,0,0,0.06); background: transparent; }
                    #edit-quill-wrap .ql-container { border: none; background: transparent; font-family: inherit; font-size: 14px; }
                    #edit-quill-wrap .ql-editor { min-height: 180px; padding: 12px 16px; }
                    #edit-quill-wrap .ql-editor.ql-blank::before { font-style: normal; color: rgba(43,54,72,0.35); }
                    #edit-quill-wrap.ql-focused { box-shadow: 0 0 0 2px rgb(0 128 0 / 0.30); }
                    #edit-quill-wrap .ql-snow .ql-stroke { stroke: rgba(43,54,72,0.55); }
                    #edit-quill-wrap .ql-snow .ql-fill { fill: rgba(43,54,72,0.55); }
                    #edit-quill-wrap .ql-snow.ql-toolbar button:hover .ql-stroke,
                    #edit-quill-wrap .ql-snow.ql-toolbar button.ql-active .ql-stroke { stroke: var(--color-tpc-primary, #008000); }
                    #edit-quill-wrap .ql-snow.ql-toolbar button:hover .ql-fill,
                    #edit-quill-wrap .ql-snow.ql-toolbar button.ql-active .ql-fill { fill: var(--color-tpc-primary, #008000); }
                    #edit-quill-wrap .ql-snow.ql-toolbar button.ql-active { color: var(--color-tpc-primary, #008000); }
                </style>

                <div id="edit-quill-wrap"
                     class="rounded-xl bg-neo-bg shadow-neo-inset-sm overflow-hidden transition"
                     x-data
                     @focusin="$el.classList.add('ql-focused')"
                     @focusout="$el.classList.remove('ql-focused')">
                    <div id="edit-quill-toolbar">
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
                    <div id="edit-quill-editor"></div>
                </div>
            </div>

            {{-- Image --}}
            <div id="edit-field-image" class="space-y-4 {{ !$typeIsText ? '' : 'hidden' }}">
                @if ($content->image_path)
                    <div>
                        <p class="text-xs font-bold text-neo-ink/60 mb-2">Current Image</p>
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('storage/' . $content->image_path) }}"
                                 class="h-28 w-48 rounded-xl object-cover shadow-neo-inset-sm bg-neo-bg" alt="">
                            <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                                <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-red-500">
                                Remove this image
                            </label>
                        </div>
                    </div>
                @endif
                <div>
                    <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">
                        {{ $content->image_path ? 'Replace Image' : 'Upload Image' }}
                        @if (!$content->image_path)<span class="text-red-500">*</span>@endif
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
                </div>
                <div>
                    <label class="block text-xs font-bold text-neo-ink/60 mb-1.5">Caption</label>
                    <input type="text" name="image_caption" value="{{ old('image_caption', $content->image_caption) }}"
                           class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-4 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition">
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 border-t border-black/[0.06] pt-5">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-tpc-primary px-6 py-2.5 text-sm font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                    Save Changes
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
            var cardText   = document.getElementById('edit-card-text');
            var cardImage  = document.getElementById('edit-card-image');
            var fieldText  = document.getElementById('edit-field-text');
            var fieldImage = document.getElementById('edit-field-image');

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
        }

        function initEditQuill() {
            if (document.getElementById('edit-quill-editor')._quill) return;

            var AlignStyle = Quill.import('attributors/style/align');
            Quill.register(AlignStyle, true);

            var editor = new Quill('#edit-quill-editor', {
                theme: 'snow',
                placeholder: 'Write your content here…',
                modules: {
                    toolbar: '#edit-quill-toolbar',
                },
            });

            editor.root._quill = editor;

            // Pre-fill with existing or old() content
            var existing = {!! json_encode(old('body', $content->body)) !!};
            if (existing) editor.root.innerHTML = existing;

            var form = document.getElementById('edit-content-section-form');
            form.addEventListener('submit', function () {
                var html = editor.root.innerHTML;
                document.getElementById('edit-body-input').value =
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
        loadQuillThen(initEditQuill);
    })();
    </script>
@endsection
