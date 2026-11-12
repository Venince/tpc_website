{{--
    Shared form fields for create & edit.
    Variables: $node (OrgChartNode|null), $parents (Collection)
--}}
@php
    $selectedParents = collect(old('parent_ids', $node?->parents?->pluck('id')->toArray() ?? []))->map(fn ($v) => (int) $v);
    $rowValue = (int) old('row', $node?->row ?? 1);
@endphp
<div class="grid gap-4 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <x-admin.card title="Person details">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.field label="Full name" name="name" required class="sm:col-span-2">
                    <input id="name" type="text" name="name" value="{{ old('name', $node?->name) }}" placeholder="e.g. Juan D. Dela Cruz" required
                           class="ui-input @error('name') is-invalid @enderror">
                </x-admin.field>

                <x-admin.field label="Position / title" name="title" required>
                    <input id="title" type="text" name="title" value="{{ old('title', $node?->title) }}" placeholder="e.g. College President" required
                           class="ui-input @error('title') is-invalid @enderror">
                </x-admin.field>

                <x-admin.field label="Department / office" name="department" hint="Optional.">
                    <input id="department" type="text" name="department" value="{{ old('department', $node?->department) }}" placeholder="e.g. Office of the President" class="ui-input">
                </x-admin.field>
            </div>
        </x-admin.card>

        <x-admin.card title="Hierarchy" description="Where this person sits in the chart.">
            <div class="space-y-5">
                <fieldset>
                    <legend class="ui-label">Reports to <span class="font-normal text-ui-muted">(select one or more)</span></legend>
                    <div class="ui-scroll max-h-64 divide-y divide-ui-line overflow-y-auto rounded-lg border border-ui-line">
                        @forelse ($parents as $parent)
                            @if (! $node || $parent->id !== $node->id)
                                <label class="flex cursor-pointer items-center gap-3 px-3 py-2.5 transition-colors hover:bg-ui-bg">
                                    <input type="checkbox" name="parent_ids[]" value="{{ $parent->id }}" class="ui-check" @checked($selectedParents->contains($parent->id))>
                                    <span class="min-w-0">
                                        <span class="block truncate text-sm font-medium text-ui-ink">{{ $parent->name }}</span>
                                        <span class="block truncate text-xs text-ui-muted">{{ $parent->title }}</span>
                                    </span>
                                </label>
                            @endif
                        @empty
                            <p class="px-3 py-4 text-center text-sm text-ui-muted">No other people yet — this will be the root node.</p>
                        @endforelse
                    </div>
                    <p class="ui-help">Leave all unchecked to make this a top-level (root) node.</p>
                    @error('parent_ids') <p class="ui-error">{{ $message }}</p> @enderror
                </fieldset>

                <fieldset>
                    <legend class="ui-label">Row placement</legend>
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach ([1 => 'Same row as siblings', 2 => 'Next row (below siblings)'] as $val => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="row" value="{{ $val }}" class="peer sr-only" @checked($rowValue === $val)>
                                <span class="flex h-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-3 text-sm font-medium text-ui-muted transition-colors hover:bg-ui-bg-2
                                             peer-checked:border-brand peer-checked:bg-brand-50 peer-checked:text-brand-dark
                                             peer-focus-visible:ring-2 peer-focus-visible:ring-brand peer-focus-visible:ring-offset-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="ui-help">Controls whether this person appears beside or below their row-mates.</p>
                </fieldset>
            </div>
        </x-admin.card>
    </div>

    <div class="space-y-4">
        {{-- Client-side checks mirror the server rules: ≤ 2 MB, 100×100 – 2000×2000 px --}}
        <x-admin.card title="Photo" description="Optional · max 2 MB · 100×100 – 2000×2000 px">
            <div x-data="{
                    preview: null, meta: '', error: '', current: @js($node?->photo ? asset('storage/' . $node->photo) : null),
                    check(e) {
                        const input = e.target, file = input.files[0];
                        this.preview = null; this.meta = ''; this.error = ''; input.setCustomValidity('');
                        if (!file) return;
                        if (file.size > 2 * 1024 * 1024) {
                            this.error = `File is too large (${(file.size / 1048576).toFixed(2)} MB). Maximum allowed size is 2 MB.`;
                            input.setCustomValidity(this.error); return;
                        }
                        const url = URL.createObjectURL(file), img = new Image();
                        img.onload = () => {
                            const w = img.naturalWidth, h = img.naturalHeight, errs = [];
                            if (w < 100 || h < 100) errs.push(`Too small (${w}×${h} px). Minimum is 100×100 px.`);
                            if (w > 2000 || h > 2000) errs.push(`Too large (${w}×${h} px). Maximum is 2000×2000 px.`);
                            if (errs.length) { this.error = errs.join(' '); input.setCustomValidity(this.error); URL.revokeObjectURL(url); return; }
                            this.preview = url; this.meta = `${w}×${h} px · ${(file.size / 1024).toFixed(0)} KB`;
                        };
                        img.src = url;
                    }
                 }">
                <div class="flex items-center gap-4">
                    <div class="grid h-24 w-24 shrink-0 place-items-center overflow-hidden rounded-full border border-ui-line bg-ui-bg-2 text-gray-300">
                        <img x-show="preview" x-cloak :src="preview" alt="Photo preview" class="h-full w-full object-cover">
                        <img x-show="!preview && current" x-cloak :src="current" alt="Current photo" class="h-full w-full object-cover">
                        <span x-show="!preview && !current"><x-admin.icon name="user-circle" class="h-10 w-10" /></span>
                    </div>
                    <div class="min-w-0 space-y-1.5">
                        <label for="photo" class="ui-btn ui-btn-secondary ui-btn-sm cursor-pointer focus-within:ring-2 focus-within:ring-brand">
                            <x-admin.icon name="upload" class="h-3.5 w-3.5" /> <span x-text="(preview || current) ? 'Change photo' : 'Choose photo'"></span>
                            <input type="file" id="photo" name="photo" accept="image/*" class="sr-only" @change="check($event)">
                        </label>
                        <p x-show="meta" x-cloak class="text-xs text-ui-muted" x-text="meta"></p>
                        <p x-show="current && !preview" x-cloak class="text-xs text-ui-muted">Current photo. Upload a new one to replace it.</p>
                    </div>
                </div>
                <p x-show="error" x-cloak class="ui-error" role="alert" x-text="error"></p>
                @error('photo') <p class="ui-error" role="alert">{{ $message }}</p> @enderror
            </div>
        </x-admin.card>

        <x-admin.card title="Visibility">
            <label class="flex cursor-pointer items-start gap-3">
                <input type="hidden" name="is_active" value="0">
                <input id="is_active" type="checkbox" name="is_active" value="1" class="ui-switch mt-0.5" role="switch" @checked(old('is_active', $node?->is_active ?? true))>
                <span>
                    <span class="block text-sm font-medium text-ui-ink">Visible on public org chart</span>
                    <span class="block text-xs text-ui-muted">Turn off to hide without deleting.</span>
                </span>
            </label>
        </x-admin.card>
    </div>
</div>
