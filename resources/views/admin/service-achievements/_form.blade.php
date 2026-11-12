{{-- Vars: $service, $achievement (ServiceAchievement|null). Wrap in a <form enctype="multipart/form-data"> — see create/edit. --}}
@php
    $a = $achievement ?? null;
    $existingImages = $a
        ? $a->images->map(fn ($img) => ['id' => $img->id, 'url' => asset('storage/' . $img->path)])->values()->all()
        : [];
@endphp
<div x-data="{
        max: 10,
        existing: @js($existingImages),
        removeIds: [],
        files: [],
        previews: [],
        dragging: false,
        get keptCount() { return this.existing.length - this.removeIds.length; },
        get remaining() { return this.max - this.keptCount - this.files.length; },
        isRemoving(id) { return this.removeIds.includes(id); },
        toggleRemove(id) {
            if (this.isRemoving(id)) { this.removeIds = this.removeIds.filter((x) => x !== id); }
            else { this.removeIds.push(id); }
        },
        addFiles(list) {
            const room = Math.max(this.remaining, 0);
            Array.from(list).slice(0, room).forEach((f) => {
                this.files.push(f);
                this.previews.push(URL.createObjectURL(f));
            });
            this.sync();
        },
        remove(i) {
            URL.revokeObjectURL(this.previews[i]);
            this.files.splice(i, 1);
            this.previews.splice(i, 1);
            this.sync();
        },
        sync() {
            const dt = new DataTransfer();
            this.files.forEach((f) => dt.items.add(f));
            this.$refs.photoInput.files = dt.files;
        }
     }" class="space-y-4">

    {{-- Photos marked for removal are sent as remove_images[] and deleted on save --}}
    <template x-for="id in removeIds" :key="id"><input type="hidden" name="remove_images[]" :value="id"></template>

    <x-admin.card title="Achievement details" class="">
        <div class="grid gap-5 sm:grid-cols-3">
            <x-admin.field label="Title" name="title" required class="sm:col-span-2">
                <input id="title" type="text" name="title" value="{{ old('title', $a?->title) }}" required @unless($a) autofocus @endunless
                       placeholder="e.g. Regional Champion — TESDA Skills Competition" class="ui-input @error('title') is-invalid @enderror">
            </x-admin.field>

            <x-admin.field label="Year" name="year" hint="Optional.">
                <input id="year" type="text" name="year" value="{{ old('year', $a?->year) }}" maxlength="50" placeholder="e.g. 2026" class="ui-input">
            </x-admin.field>

            <x-admin.field label="Description" name="description" hint="Optional · max 2000 characters." class="sm:col-span-3">
                <textarea id="description" name="description" rows="4" maxlength="2000" placeholder="A short note about this achievement…" class="ui-textarea">{{ old('description', $a?->description) }}</textarea>
            </x-admin.field>
        </div>
    </x-admin.card>

    @if ($a)
        <x-admin.card class="" x-show="existing.length" x-cloak>
            <x-slot:actions>
                <span class="text-xs text-ui-muted">
                    <span x-text="keptCount"></span> kept<span x-show="removeIds.length"> · <span x-text="removeIds.length"></span> to remove</span>
                </span>
            </x-slot:actions>
            <p class="ui-label">Current photos</p>
            <ul class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-5">
                <template x-for="img in existing" :key="img.id">
                    <li class="relative aspect-square overflow-hidden rounded-lg border border-ui-line bg-ui-bg-2">
                        <img :src="img.url" alt="" class="h-full w-full object-cover transition" :class="isRemoving(img.id) ? 'opacity-30 grayscale' : ''">
                        <span x-show="isRemoving(img.id)" x-cloak class="absolute inset-x-0 bottom-0 bg-red-600/90 px-1 py-0.5 text-center text-[10px] font-semibold uppercase tracking-wide text-white">Will be removed</span>
                        <button type="button" @click="toggleRemove(img.id)"
                                class="absolute right-1 top-1 grid h-7 w-7 place-items-center rounded-full text-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
                                :class="isRemoving(img.id) ? 'bg-brand' : 'bg-gray-900/70 hover:bg-red-600'"
                                :aria-label="isRemoving(img.id) ? 'Keep this photo' : 'Remove this photo'">
                            <x-admin.icon name="trash" class="h-3.5 w-3.5" x-show="!isRemoving(img.id)" />
                            <x-admin.icon name="arrow-path" class="h-3.5 w-3.5" x-show="isRemoving(img.id)" x-cloak />
                        </button>
                    </li>
                </template>
            </ul>
        </x-admin.card>
    @endif

    <x-admin.card :title="$a ? 'Add photos' : 'Photos'" description="Optional · 10 photos total per achievement" class="">
        <label for="photoInput" class="ui-dropzone relative cursor-pointer focus-within:ring-2 focus-within:ring-brand"
               :class="dragging ? '!border-brand !bg-brand-50' : ''"
               @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)">
            <x-admin.icon name="upload" class="h-6 w-6 text-gray-400" />
            <span x-show="remaining > 0" class="font-medium text-ui-ink">Tap to select photos, or drag &amp; drop</span>
            <span x-show="remaining <= 0" x-cloak class="font-medium text-ui-ink">Photo limit reached (10). Remove a photo to add another.</span>
            <span class="text-xs">JPG, PNG or WEBP · max 5 MB each · <span x-text="Math.max(remaining, 0)"></span> more allowed</span>
            <input id="photoInput" type="file" name="photos[]" multiple accept="image/png,image/jpeg,image/webp" x-ref="photoInput" class="sr-only"
                   :disabled="remaining <= 0" @change="addFiles($event.target.files)">
        </label>
        @error('photos') <p class="ui-error" role="alert">{{ $message }}</p> @enderror
        @foreach ($errors->get('photos.*') as $messages)
            @foreach ($messages as $m)<p class="ui-error" role="alert">{{ $m }}</p>@endforeach
        @endforeach

        <p x-show="files.length" x-cloak class="mt-3 text-xs text-ui-muted"><span x-text="files.length"></span> <span x-text="files.length === 1 ? 'photo' : 'photos'"></span> selected</p>
        <ul x-show="previews.length" x-cloak class="mt-2 grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-5">
            <template x-for="(src, i) in previews" :key="src">
                <li class="relative aspect-square overflow-hidden rounded-lg border border-ui-line bg-ui-bg-2">
                    <img :src="src" alt="" class="h-full w-full object-cover">
                    <button type="button" @click="remove(i)" class="absolute right-1 top-1 grid h-6 w-6 place-items-center rounded-full bg-gray-900/70 text-white transition-colors hover:bg-red-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white" aria-label="Remove this photo">
                        <x-admin.icon name="x" class="h-3.5 w-3.5" />
                    </button>
                </li>
            </template>
        </ul>
    </x-admin.card>
</div>
