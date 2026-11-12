@extends('admin.layout', ['title' => 'Add Section'])

@section('content')
    <x-admin.page-header title="Add section" :subtitle="'For ' . $program->name"
        :breadcrumbs="['Programs' => route('admin.programs.index'), $program->code => route('admin.programs.show', $program), 'Details' => route('admin.programs.details.index', $program), 'Add section' => null]" />

    <form method="POST" action="{{ route('admin.programs.details.store', $program) }}" enctype="multipart/form-data"
          x-data="{
              type: '{{ old('type', 'text') }}',
              items: {{ json_encode(old('items', [''])) }},
              addItem() { this.items.push(''); },
              removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); }
          }">
        @csrf

        <x-admin.card title="Section content" class="">
            <div class="space-y-5">
                {{-- Type selector --}}
                <fieldset>
                    <legend class="ui-label ui-required">Section type</legend>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                        @foreach (['text' => 'Text block', 'list' => 'Bullet list', 'gallery' => 'Gallery image'] as $val => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="{{ $val }}" x-model="type" class="peer sr-only">
                                <span class="flex h-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-3 text-sm font-medium text-ui-muted transition-colors
                                             hover:bg-ui-bg-2 peer-checked:border-brand peer-checked:bg-brand-50 peer-checked:text-brand-dark
                                             peer-focus-visible:ring-2 peer-focus-visible:ring-brand peer-focus-visible:ring-offset-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('type') <p class="ui-error">{{ $message }}</p> @enderror
                </fieldset>

                <x-admin.field label="Section heading" name="heading" hint="Optional — e.g. About this Program, Career Opportunities.">
                    <input id="heading" type="text" name="heading" value="{{ old('heading') }}" class="ui-input" placeholder="Section heading">
                </x-admin.field>

                {{-- TEXT --}}
                <div x-show="type === 'text'" x-cloak>
                    <x-admin.field label="Body" name="body">
                        <textarea id="body" name="body" rows="7" class="ui-textarea" placeholder="Write paragraph content here…">{{ old('body') }}</textarea>
                    </x-admin.field>
                </div>

                {{-- LIST --}}
                <div x-show="type === 'list'" x-cloak>
                    <p class="ui-label">Items</p>
                    <div class="space-y-2">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex items-center gap-2">
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-brand" aria-hidden="true"></span>
                                <input type="text" :name="'items[' + index + ']'" x-model="items[index]" class="ui-input" placeholder="Enter item text" :aria-label="'Item ' + (index + 1)">
                                <button type="button" @click="removeItem(index)" class="ui-btn ui-btn-ghost ui-btn-icon ui-btn-sm" aria-label="Remove item" title="Remove">
                                    <x-admin.icon name="x" class="h-4 w-4" />
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addItem()" class="ui-btn ui-btn-secondary ui-btn-sm mt-3"><x-admin.icon name="plus" class="h-3.5 w-3.5" /> Add item</button>
                    @error('items') <p class="ui-error">{{ $message }}</p> @enderror
                </div>

                {{-- GALLERY --}}
                <div x-show="type === 'gallery'" x-cloak class="space-y-5">
                    <x-admin.image-upload name="image" id="image" label="Image" />
                    <x-admin.field label="Caption" name="caption" hint="Optional — shown below the image.">
                        <input id="caption" type="text" name="caption" value="{{ old('caption') }}" class="ui-input" placeholder="Short caption">
                    </x-admin.field>
                </div>

                <x-admin.field label="Display order" name="order" hint="Leave blank to add at the end.">
                    <input id="order" type="number" name="order" value="{{ old('order') }}" min="0" class="ui-input ui-input-auto" style="width:8rem">
                </x-admin.field>
            </div>
        </x-admin.card>

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.programs.details.index', $program)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Add section</x-admin.button>
        </div>
    </form>
@endsection
