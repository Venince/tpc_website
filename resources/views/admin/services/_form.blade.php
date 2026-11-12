{{-- Vars: $service (Service|null), $platforms --}}
@php $s = $service ?? null; @endphp
<div class="grid gap-4 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <x-admin.card title="Service information">
            <div class="space-y-5">
                <x-admin.field label="Service title" name="title" required>
                    <input id="title" type="text" name="title" value="{{ old('title', $s?->title) }}" required
                           placeholder="e.g. Technical Vocational Training" class="ui-input @error('title') is-invalid @enderror">
                </x-admin.field>

                <x-admin.field label="Short description" name="description">
                    <textarea id="description" name="description" rows="4" class="ui-textarea @error('description') is-invalid @enderror"
                              placeholder="A short summary shown on the Services page…">{{ old('description', $s?->description) }}</textarea>
                </x-admin.field>
            </div>
        </x-admin.card>

        <x-admin.card title="Online presence">
            @include('admin.services._social_links', ['existing' => old('social_links', $s?->social_links ?? [])])
        </x-admin.card>

        <x-admin.card title="Forms & downloads">
            @include('admin.services._attachments', ['attachments' => $s?->attachments ?? collect()])
        </x-admin.card>
    </div>

    <div class="space-y-4">
        <x-admin.card title="Featured image">
            <x-admin.image-upload name="featured_image" id="featured_image"
                :current="$s?->featured_image_path ? asset('storage/' . $s->featured_image_path) : null"
                remove-name="remove_image" hint="JPG, PNG or WEBP · max 5 MB" />
        </x-admin.card>

        <x-admin.card title="Visibility">
            <label class="flex cursor-pointer items-start gap-3">
                <input type="hidden" name="is_active" value="0">
                <input id="is_active" type="checkbox" name="is_active" value="1" class="ui-switch mt-0.5" role="switch"
                       @checked(old('is_active', $s ? $s->is_active : 1))>
                <span>
                    <span class="block text-sm font-medium text-ui-ink">Active</span>
                    <span class="block text-xs text-ui-muted">Visible on the public Services page.</span>
                </span>
            </label>
        </x-admin.card>
    </div>
</div>
