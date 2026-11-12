{{-- Vars: $service, $content (ServiceContent|null) --}}
@php
    $c = $content ?? null;
    $type = old('type', $c?->type ?? 'text');
@endphp
<x-admin.card title="Section content" class="">
    <div class="space-y-5" x-data="{ type: '{{ $type }}' }">
        <fieldset>
            <legend class="ui-label ui-required">Section type</legend>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ([
                    'text'  => ['Text block',  'Paragraph content', 'document-text'],
                    'image' => ['Image block', 'Upload a photo',    'photo'],
                ] as $val => [$label, $desc, $icon])
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="{{ $val }}" x-model="type" class="peer sr-only">
                        <span class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white px-4 py-3 transition-colors hover:bg-ui-bg-2
                                     peer-checked:border-brand peer-checked:bg-brand-50
                                     peer-focus-visible:ring-2 peer-focus-visible:ring-brand peer-focus-visible:ring-offset-2">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-ui-bg-2 text-gray-500"
                                  :class="type === '{{ $val }}' ? '!bg-white !text-brand' : ''"><x-admin.icon :name="$icon" class="h-5 w-5" /></span>
                            <span class="min-w-0">
                                <span class="block text-sm font-medium text-ui-ink">{{ $label }}</span>
                                <span class="block text-xs text-ui-muted">{{ $desc }}</span>
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>
            @error('type') <p class="ui-error">{{ $message }}</p> @enderror
        </fieldset>

        <x-admin.field label="Section heading" name="heading" hint="Optional — e.g. What We Offer.">
            <input id="heading" type="text" name="heading" value="{{ old('heading', $c?->heading) }}" placeholder="Section heading" class="ui-input">
        </x-admin.field>

        {{-- Text body --}}
        <div x-show="type === 'text'" x-cloak>
            <p class="ui-label ui-required">Content</p>
            <x-admin.rich-editor name="body" :value="old('body', $c?->body)" />
            @error('body') <p class="ui-error">{{ $message }}</p> @enderror
        </div>

        {{-- Image --}}
        <div x-show="type === 'image'" x-cloak class="space-y-5">
            <x-admin.image-upload name="image" id="image" :label="($c?->image_path) ? 'Replace image' : 'Image'" :required="! $c?->image_path"
                :current="$c?->image_path ? asset('storage/' . $c->image_path) : null" remove-name="remove_image"
                hint="JPG, PNG or WEBP · max 8 MB" :max-mb="8" />
            <x-admin.field label="Caption" name="image_caption" hint="Optional — shown below the image.">
                <input id="image_caption" type="text" name="image_caption" value="{{ old('image_caption', $c?->image_caption) }}" placeholder="e.g. Students during lab training" class="ui-input">
            </x-admin.field>
        </div>
    </div>
</x-admin.card>
