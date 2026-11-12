{{-- Vars: $aboutSlide (AboutSlide|null) --}}
@php $sl = $aboutSlide ?? null; @endphp
<div class=" space-y-4">
    <x-admin.card title="Slide image" description="JPG, PNG or WEBP · max 4 MB · recommended ratio 2:1 (e.g. 1120 × 560)">
        <x-admin.image-upload name="image" id="image-input" wide :required="! $sl" :max-mb="4"
            :current="$sl ? asset('storage/' . $sl->image_path) : null"
            :hint="$sl ? 'Leave unchanged to keep the current image.' : 'Required.'"
            accept="image/png,image/jpeg,image/webp" />
    </x-admin.card>

    <x-admin.card title="Slide details">
        <div class="space-y-5">
            <x-admin.field label="Title" name="title" hint="Optional — e.g. Campus Life.">
                <input id="title" type="text" name="title" value="{{ old('title', $sl?->title) }}" placeholder="e.g. Campus Life" class="ui-input @error('title') is-invalid @enderror">
            </x-admin.field>

            {{-- The controller reads is_active via boolean(); the hidden 0 keeps "off" working --}}
            <label class="flex cursor-pointer items-start gap-3">
                <input type="hidden" name="is_active" value="0">
                <input id="is_active" type="checkbox" name="is_active" value="1" class="ui-switch mt-0.5" role="switch"
                       @checked(old('is_active', $sl ? $sl->is_active : true))>
                <span>
                    <span class="block text-sm font-medium text-ui-ink">Visible on homepage</span>
                    <span class="block text-xs text-ui-muted">Show this slide in the About section carousel.</span>
                </span>
            </label>

            @if ($sl)
                <p class="rounded-lg bg-ui-bg px-3 py-2 text-xs text-ui-muted">This slide is currently at position <strong class="text-ui-ink">#{{ $sl->sort_order }}</strong> in the carousel.</p>
            @endif
        </div>
    </x-admin.card>
</div>
