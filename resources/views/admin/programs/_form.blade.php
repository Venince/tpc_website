{{--
    Shared program form fields.
    Vars: $program (Program|null), $platforms
--}}
@php $p = $program ?? null; @endphp
<div class="grid gap-4 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <x-admin.card title="Program information" description="Core details shown on the Academics page.">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.field label="Code" name="code" required hint="Short unique code, e.g. BSIT.">
                    <input id="code" name="code" type="text" value="{{ old('code', $p?->code) }}" placeholder="e.g. BSIT"
                           maxlength="30" required class="ui-input @error('code') is-invalid @enderror">
                </x-admin.field>

                <x-admin.field label="Department" name="department">
                    <input id="department" name="department" type="text" value="{{ old('department', $p?->department) }}"
                           placeholder="e.g. Technology" class="ui-input @error('department') is-invalid @enderror">
                </x-admin.field>

                <x-admin.field label="Program name" name="name" required class="sm:col-span-2">
                    <input id="name" name="name" type="text" value="{{ old('name', $p?->name) }}"
                           placeholder="Bachelor of Science in…" required class="ui-input @error('name') is-invalid @enderror">
                </x-admin.field>

                <x-admin.field label="Description" name="description" class="sm:col-span-2">
                    <textarea id="description" name="description" rows="5" placeholder="Brief description of the program…"
                              class="ui-textarea @error('description') is-invalid @enderror">{{ old('description', $p?->description) }}</textarea>
                </x-admin.field>
            </div>
        </x-admin.card>

        <x-admin.card title="Online presence">
            @include('admin.services._social_links', ['existing' => old('social_links', $p?->social_links ?? [])])
        </x-admin.card>
    </div>

    <div class="space-y-4">
        <x-admin.card title="Program logo">
            <x-admin.image-upload name="logo" id="logo"
                :current="$p?->logo_path ? asset('storage/' . $p->logo_path) : null"
                remove-name="remove_logo" />
        </x-admin.card>

        <x-admin.card title="Visibility">
            <label class="flex cursor-pointer items-start gap-3">
                <input id="is_active" type="checkbox" name="is_active" value="1" class="ui-switch mt-0.5" role="switch"
                       @checked(old('is_active', $p ? $p->is_active : true))>
                <span>
                    <span class="block text-sm font-medium text-ui-ink">Active</span>
                    <span class="block text-xs text-ui-muted">Visible on the public Academics page.</span>
                </span>
            </label>
        </x-admin.card>
    </div>
</div>
