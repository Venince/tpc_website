@props([
    'name'        => 'body',
    'value'       => '',
    'placeholder' => 'Write your content here…',
    'minHeight'   => '180px',
])
{{-- Quill editor. The HTML is copied into the hidden <input name="{{ $name }}"> when the parent form submits. Quill loads on demand. --}}
<div x-data="{
        init() {
            const mount = () => {
                const el = this.$refs.editor;
                if (el.__quill) return;
                Quill.register(Quill.import('attributors/style/align'), true);
                const q = new Quill(el, { theme: 'snow', placeholder: @js($placeholder), modules: { toolbar: this.$refs.toolbar } });
                el.__quill = q;
                const initial = this.$refs.input.value;
                if (initial) q.root.innerHTML = initial;
                const form = this.$root.closest('form');
                if (form) form.addEventListener('submit', () => {
                    const html = q.root.innerHTML;
                    this.$refs.input.value = (html === '<p><br></p>' || html.trim() === '') ? '' : html;
                });
            };
            if (window.Quill) { mount(); return; }
            let s = document.querySelector('script[data-quill]');
            if (!s) {
                const l = document.createElement('link');
                l.rel = 'stylesheet'; l.href = 'https://cdn.quilljs.com/1.3.7/quill.snow.css';
                document.head.appendChild(l);
                s = document.createElement('script');
                s.src = 'https://cdn.quilljs.com/1.3.7/quill.min.js'; s.dataset.quill = '1';
                document.head.appendChild(s);
            }
            s.addEventListener('load', mount, { once: true });
        }
     }"
     class="ui-rte" style="--rte-min: {{ $minHeight }}">
    <input type="hidden" name="{{ $name }}" x-ref="input" value="{{ $value }}">
    <div x-ref="toolbar">
        <span class="ql-formats">
            <button type="button" class="ql-bold" title="Bold" aria-label="Bold"></button>
            <button type="button" class="ql-italic" title="Italic" aria-label="Italic"></button>
            <button type="button" class="ql-underline" title="Underline" aria-label="Underline"></button>
        </span>
        <span class="ql-formats">
            <button type="button" class="ql-list" value="ordered" title="Numbered list" aria-label="Numbered list"></button>
            <button type="button" class="ql-list" value="bullet" title="Bullet list" aria-label="Bullet list"></button>
            <button type="button" class="ql-indent" value="-1" title="Decrease indent" aria-label="Decrease indent"></button>
            <button type="button" class="ql-indent" value="+1" title="Increase indent" aria-label="Increase indent"></button>
        </span>
        <span class="ql-formats">
            <select class="ql-align" title="Alignment" aria-label="Alignment">
                <option selected></option>
                <option value="center"></option>
                <option value="right"></option>
                <option value="justify"></option>
            </select>
        </span>
    </div>
    <div x-ref="editor"></div>
</div>
