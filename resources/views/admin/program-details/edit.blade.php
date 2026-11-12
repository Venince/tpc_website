@extends('admin.layout', ['title' => 'Edit Section'])

@section('content')
    <x-admin.page-header title="Edit section" subtitle="The section type cannot be changed after creation."
        :breadcrumbs="['Programs' => route('admin.programs.index'), $program->code => route('admin.programs.show', $program), 'Details' => route('admin.programs.details.index', $program), 'Edit section' => null]">
        <x-admin.badge type="success" class="uppercase">{{ $detail->type }}</x-admin.badge>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.programs.details.update', [$program, $detail]) }}" enctype="multipart/form-data"
          x-data="{
              items: {{ json_encode(old('items', $detail->items ?? [''])) }},
              addItem() { this.items.push(''); },
              removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); }
          }">
        @csrf @method('PATCH')

        <x-admin.card title="Section content" class="">
            <div class="space-y-5">
                <x-admin.field label="Section heading" name="heading" hint="Optional.">
                    <input id="heading" type="text" name="heading" value="{{ old('heading', $detail->heading) }}" class="ui-input" placeholder="e.g. About this Program">
                </x-admin.field>

                @if ($detail->type === 'text')
                    <x-admin.field label="Body" name="body">
                        <textarea id="body" name="body" rows="7" class="ui-textarea">{{ old('body', $detail->body) }}</textarea>
                    </x-admin.field>
                @endif

                @if ($detail->type === 'list')
                    <div>
                        <p class="ui-label">Items</p>
                        <div class="space-y-2">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-brand" aria-hidden="true"></span>
                                    <input type="text" :name="'items[' + index + ']'" x-model="items[index]" class="ui-input" :aria-label="'Item ' + (index + 1)">
                                    <button type="button" @click="removeItem(index)" class="ui-btn ui-btn-ghost ui-btn-icon ui-btn-sm" aria-label="Remove item" title="Remove">
                                        <x-admin.icon name="x" class="h-4 w-4" />
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="addItem()" class="ui-btn ui-btn-secondary ui-btn-sm mt-3"><x-admin.icon name="plus" class="h-3.5 w-3.5" /> Add item</button>
                        @error('items') <p class="ui-error">{{ $message }}</p> @enderror
                    </div>
                @endif

                @if ($detail->type === 'gallery')
                    <x-admin.image-upload name="image" id="image" label="Image"
                        :current="$detail->image_path ? asset('storage/' . $detail->image_path) : null" />
                    <x-admin.field label="Caption" name="caption" hint="Optional — shown below the image.">
                        <input id="caption" type="text" name="caption" value="{{ old('caption', $detail->caption) }}" class="ui-input">
                    </x-admin.field>
                @endif

                <x-admin.field label="Display order" name="order">
                    <input id="order" type="number" name="order" value="{{ old('order', $detail->order) }}" min="0" class="ui-input ui-input-auto" style="width:8rem">
                </x-admin.field>
            </div>
        </x-admin.card>

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.programs.details.index', $program)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save changes</x-admin.button>
        </div>
    </form>
@endsection
