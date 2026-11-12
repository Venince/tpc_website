@props([
    'name',
    'label'      => null,
    'current'    => null,                       // URL of the existing image (edit forms)
    'removeName' => null,                       // checkbox name that deletes the current image (e.g. remove_logo)
    'accept'     => 'image/png,image/jpeg,image/webp',
    'hint'       => 'PNG, JPG or WEBP · max 5 MB',
    'maxMb'      => 5,
    'required'   => false,
    'rounded'    => 'rounded-lg',               // rounded-lg | rounded-full
    'wide'       => false,                      // true → 2:1 preview box (slides, banners)
])
@php
    $id = $attributes->get('id', $name);
    $placeholderShow = $current ? '!preview && removed' : '!preview';
@endphp
{{-- Image picker with live preview, size check, and replace / remove for the stored image. --}}
<div x-data="{
        preview: null, fileName: '', fileSize: '', tooBig: false, removed: false,
        pick(e) {
            const f = e.target.files[0];
            if (!f) { this.clear(); return; }
            this.tooBig = f.size > {{ (float) $maxMb }} * 1024 * 1024;
            this.fileName = f.name;
            this.fileSize = (f.size / 1048576).toFixed(2) + ' MB';
            this.preview = URL.createObjectURL(f);
        },
        clear() { this.preview = null; this.fileName = ''; this.fileSize = ''; this.tooBig = false; this.$refs.input.value = ''; }
     }">
    @if ($label)
        <label for="{{ $id }}" @class(['ui-label', 'ui-required' => $required])>{{ $label }}</label>
    @endif

    <div class="flex items-start gap-4">
        <div class="grid {{ $wide ? 'h-24 w-48' : 'h-20 w-20' }} shrink-0 place-items-center overflow-hidden border border-ui-line bg-ui-bg-2 text-gray-300 {{ $rounded }}">
            <img x-show="preview" x-cloak :src="preview" alt="Selected image preview" class="h-full w-full object-cover">
            @if ($current)
                <img x-show="!preview && !removed" src="{{ $current }}" alt="Current image" class="h-full w-full object-cover">
            @endif
            <span x-show="{{ $placeholderShow }}" x-cloak><x-admin.icon name="photo" class="h-8 w-8" /></span>
        </div>

        <div class="min-w-0 flex-1 space-y-2">
            <input x-ref="input" id="{{ $id }}" type="file" name="{{ $name }}" accept="{{ $accept }}" class="ui-file"
                   @change="pick($event)" aria-describedby="{{ $id }}-hint" @required($required && ! $current)>
            <p id="{{ $id }}-hint" class="ui-help !mt-0">
                @if ($current) Choose a file to replace the current image. @endif {{ $hint }}
            </p>

            <p x-show="tooBig" x-cloak class="ui-error" role="alert">This file is larger than {{ $maxMb }} MB and will be rejected by the server.</p>

            <div x-show="preview" x-cloak class="flex items-center justify-between gap-3 rounded-lg bg-ui-bg px-3 py-2 text-xs">
                <span class="min-w-0 truncate text-ui-ink"><span x-text="fileName"></span> <span class="text-ui-muted" x-text="'· ' + fileSize"></span></span>
                <button type="button" class="font-medium text-red-700 hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600 rounded" @click="clear()">Clear</button>
            </div>

            @if ($current && $removeName)
                <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-ui-ink">
                    <input type="checkbox" name="{{ $removeName }}" value="1" class="ui-check" x-model="removed">
                    Remove current image
                </label>
            @endif
        </div>
    </div>

    @error($name)
        <p class="ui-error" role="alert">{{ $message }}</p>
    @enderror
</div>
