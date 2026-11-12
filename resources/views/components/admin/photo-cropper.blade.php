@props([
    'name'       => 'photo_crop',     // hidden field that receives the cropped image as a base64 data URL
    'label'      => 'Photo',
    'current'    => null,             // URL of the stored photo (edit forms)
    'removeName' => null,             // checkbox that deletes the stored photo (e.g. remove_photo)
    'hint'       => 'PNG, JPG or WEBP · cropped to a square',
    'aspect'     => 1,
    'size'       => 400,              // output width in px
    'rounded'    => 'rounded-full',
    'required'   => false,
])
@php
    $uid = 'crop' . substr(md5($name . $label), 0, 6);
    $height = (int) round($size / $aspect);
@endphp
{{-- Pick → crop (Cropper.js, loaded by the admin layout) → preview. The result is sent in the hidden $name field. --}}
<div x-data="{
        cropped: '', preview: null, open: false, removed: false,
        stage() { return document.getElementById('{{ $uid }}-stage'); },
        pick(e) {
            const f = e.target.files[0];
            if (!f) return;
            const reader = new FileReader();
            reader.onload = (ev) => this.openCrop(ev.target.result);
            reader.readAsDataURL(f);
            e.target.value = '';
        },
        openCrop(src) {
            if (typeof Cropper === 'undefined') { window.tpcToast && window.tpcToast('error', 'The image editor is still loading. Please try again in a moment.'); return; }
            this.open = true;
            this.$nextTick(() => {
                const st = this.stage();
                st.innerHTML = '';
                const img = document.createElement('img');
                img.style.cssText = 'display:block;max-width:100%;';
                img.onload = () => {
                    if (st._c) st._c.destroy();
                    st._c = new Cropper(img, { aspectRatio: {{ (float) $aspect }}, viewMode: 1, dragMode: 'move', autoCropArea: 0.9,
                        movable: true, zoomable: true, rotatable: false, scalable: false,
                        cropBoxMovable: false, cropBoxResizable: false, highlight: false, background: true, responsive: true });
                };
                st.appendChild(img);
                img.src = src;
            });
        },
        close() {
            const st = this.stage();
            if (st && st._c) { st._c.destroy(); st._c = null; }
            this.open = false;
        },
        confirm() {
            const st = this.stage();
            if (!st || !st._c) return;
            const canvas = st._c.getCroppedCanvas({ width: {{ (int) $size }}, height: {{ $height }} });
            this.cropped = canvas.toDataURL('image/jpeg', 0.85);
            this.preview = this.cropped;
            this.removed = false;
            this.close();
        }
     }"
     @keydown.escape.window="open && close()">

    <p @class(['ui-label', 'ui-required' => $required])>{{ $label }}</p>

    <div class="flex items-center gap-4">
        <div class="grid h-24 w-24 shrink-0 place-items-center overflow-hidden border border-ui-line bg-ui-bg-2 text-gray-300 {{ $rounded }}">
            <img x-show="preview" x-cloak :src="preview" alt="Cropped photo preview" class="h-full w-full object-cover">
            @if ($current)
                <img x-show="!preview && !removed" src="{{ $current }}" alt="Current photo" class="h-full w-full object-cover">
            @endif
            <span x-show="{{ $current ? '!preview && removed' : '!preview' }}" x-cloak><x-admin.icon name="user-circle" class="h-10 w-10" /></span>
        </div>

        <div class="min-w-0 space-y-2">
            <label class="ui-btn ui-btn-secondary ui-btn-sm cursor-pointer focus-within:ring-2 focus-within:ring-brand focus-within:ring-offset-2">
                <x-admin.icon name="upload" class="h-3.5 w-3.5" />
                <span x-text="(preview || {{ $current ? 'true' : 'false' }}) ? 'Change photo' : 'Choose photo'"></span>
                <input type="file" accept="image/png,image/jpeg,image/webp" class="sr-only" @change="pick($event)" aria-label="{{ $label }} file">
            </label>
            <p class="ui-help !mt-0">{{ $hint }}</p>
            @if ($current && $removeName)
                <label class="flex cursor-pointer items-center gap-2 text-sm text-ui-ink">
                    <input type="checkbox" name="{{ $removeName }}" value="1" class="ui-check" x-model="removed">
                    Remove current photo
                </label>
            @endif
        </div>
    </div>

    <input type="hidden" name="{{ $name }}" :value="cropped">
    @error($name) <p class="ui-error" role="alert">{{ $message }}</p> @enderror

    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[70] flex items-end justify-center sm:items-center sm:p-6" role="dialog" aria-modal="true" aria-labelledby="{{ $uid }}-title">
            <div class="absolute inset-0 bg-gray-900/50" @click="close()" aria-hidden="true"></div>
            <div class="relative flex max-h-[95vh] w-full flex-col overflow-hidden rounded-t-2xl border border-ui-line bg-white shadow-ui-lg sm:max-w-lg sm:rounded-xl">
                <div class="flex items-center justify-between border-b border-ui-line px-5 py-3.5">
                    <h3 id="{{ $uid }}-title" class="text-base font-semibold text-ui-ink">Crop photo</h3>
                    <button type="button" class="ui-btn ui-btn-ghost ui-btn-icon ui-btn-sm" @click="close()" aria-label="Close"><x-admin.icon name="x" class="h-4 w-4" /></button>
                </div>
                <div id="{{ $uid }}-stage" class="bg-gray-900/5" style="height:340px;"></div>
                <div class="flex flex-col-reverse gap-2 border-t border-ui-line bg-ui-bg px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-ui-muted">Drag to reposition · scroll or pinch to zoom</p>
                    <div class="flex gap-2">
                        <button type="button" class="ui-btn ui-btn-secondary" @click="close()">Cancel</button>
                        <button type="button" class="ui-btn ui-btn-primary" @click="confirm()">Use photo</button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
