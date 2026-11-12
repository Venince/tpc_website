@props([
    'name'     => 'photos',       // submitted as name[]
    'label'    => 'Photos',
    'max'      => 10,
    'hint'     => 'PNG, JPG or WEBP · max 5 MB each',
    'required' => false,
])
@php $id = $name . '-input'; @endphp
{{-- Drag & drop / click multi-image picker with removable previews. Keeps the real <input> in sync via DataTransfer. --}}
<div x-data="{
        files: [], previews: [], over: false, max: {{ (int) $max }},
        add(list) {
            const merged = [...this.files, ...Array.from(list)].filter(f => f.type.startsWith('image/')).slice(0, this.max);
            this.previews.forEach(p => URL.revokeObjectURL(p.url));
            this.files = merged;
            this.previews = merged.map(f => ({ url: URL.createObjectURL(f), name: f.name, size: (f.size / 1048576).toFixed(1) + ' MB', big: f.size > 5 * 1048576 }));
            this.sync();
        },
        drop(i) {
            const next = this.files.filter((_, idx) => idx !== i);
            this.previews.forEach(p => URL.revokeObjectURL(p.url));
            this.files = next;
            this.previews = next.map(f => ({ url: URL.createObjectURL(f), name: f.name, size: (f.size / 1048576).toFixed(1) + ' MB', big: f.size > 5 * 1048576 }));
            this.sync();
        },
        sync() {
            const dt = new DataTransfer();
            this.files.forEach(f => dt.items.add(f));
            this.$refs.input.files = dt.files;
        }
     }">
    <label for="{{ $id }}" @class(['ui-label', 'ui-required' => $required])>
        {{ $label }} <span class="font-normal text-ui-muted">(up to {{ $max }})</span>
    </label>

    <label for="{{ $id }}" class="ui-dropzone cursor-pointer focus-within:ring-2 focus-within:ring-brand"
           :class="over ? '!border-brand !bg-brand-50' : ''"
           @dragover.prevent="over = true" @dragleave.prevent="over = false" @drop.prevent="over = false; add($event.dataTransfer.files)">
        <x-admin.icon name="upload" class="h-6 w-6 text-gray-400" />
        <span class="font-medium text-ui-ink">Click to select or drag &amp; drop images</span>
        <span class="text-xs">{{ $hint }}</span>
        <input x-ref="input" id="{{ $id }}" type="file" name="{{ $name }}[]" accept="image/png,image/jpeg,image/webp" multiple class="sr-only"
               @change="add($event.target.files)">
    </label>

    @error($name) <p class="ui-error" role="alert">{{ $message }}</p> @enderror
    @error($name . '.*') <p class="ui-error" role="alert">{{ $message }}</p> @enderror

    <div x-show="previews.length" x-cloak class="mt-3">
        <p class="mb-2 text-xs text-ui-muted"><span x-text="previews.length"></span> image<span x-show="previews.length !== 1">s</span> selected</p>
        <ul class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-5">
            <template x-for="(p, i) in previews" :key="p.url">
                <li class="relative aspect-square overflow-hidden rounded-lg border border-ui-line bg-ui-bg-2">
                    <img :src="p.url" :alt="p.name" class="h-full w-full object-cover">
                    <span x-show="p.big" class="absolute inset-x-0 bottom-0 bg-red-600/90 px-1 py-0.5 text-center text-[10px] font-medium text-white">Over 5 MB</span>
                    <button type="button" @click="drop(i)" class="absolute right-1 top-1 grid h-6 w-6 place-items-center rounded-full bg-gray-900/70 text-white transition-colors hover:bg-red-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
                            :aria-label="'Remove ' + p.name">
                        <x-admin.icon name="x" class="h-3.5 w-3.5" />
                    </button>
                </li>
            </template>
        </ul>
    </div>
</div>
