@extends('admin.layout', ['title' => 'Add Section'])

@section('content')

    <div class="mb-5">
        <a href="{{ route('admin.programs.details.index', $program) }}"
           class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
            ← Back to {{ $program->code }} Details
        </a>
    </div>

    <div class="max-w-xl"
         x-data="{
            type: '{{ old('type', 'text') }}',
            items: {{ json_encode(old('items', [''])) }},
            addItem() { this.items.push(''); },
            removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); }
         }">

        <h2 class="text-base font-bold text-tpc-ink mb-1">Add Section</h2>
        <p class="text-sm text-tpc-ink/50 mb-6">
            For: <span class="font-semibold text-tpc-ink">{{ $program->name }}</span>
        </p>

        <form method="POST"
              action="{{ route('admin.programs.details.store', $program) }}"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf

            {{-- Type selector --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-2">Section Type</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach (['text' => 'Text Block', 'list' => 'Bullet List', 'gallery' => 'Gallery Image'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="{{ $val }}" x-model="type" class="sr-only" />
                            <span class="block rounded-xl border-2 px-3 py-2.5 text-center text-xs font-semibold transition"
                                  :class="type === '{{ $val }}'
                                      ? 'border-tpc-primary bg-tpc-primary text-white'
                                      : 'border-gray-200 text-tpc-ink/60 hover:border-tpc-primary/40'">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Heading (all types) --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Section Heading <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <input type="text" name="heading" value="{{ old('heading') }}"
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
                       placeholder="e.g. About this Program, Achievements, Career Opportunities" />
                @error('heading') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- TEXT fields --}}
            <div x-show="type === 'text'" x-cloak>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Body</label>
                <textarea name="body" rows="6"
                          class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition resize-y"
                          placeholder="Write paragraph content here...">{{ old('body') }}</textarea>
                @error('body') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- LIST fields --}}
            <div x-show="type === 'list'" x-cloak class="space-y-3">
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60">Items</label>

                <template x-for="(item, index) in items" :key="index">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0 h-1.5 w-1.5 rounded-full bg-tpc-primary mt-0.5"></span>
                        <input type="text" :name="'items[' + index + ']'" x-model="items[index]"
                               class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
                               placeholder="Enter item text" />
                        <button type="button" @click="removeItem(index)"
                                class="shrink-0 text-red-400 hover:text-red-600 transition text-lg leading-none"
                                title="Remove">×</button>
                    </div>
                </template>

                <button type="button" @click="addItem()"
                        class="text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">
                    + Add Item
                </button>
                @error('items') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- GALLERY fields --}}
            <div x-show="type === 'gallery'" x-cloak class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Image</label>
                    <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                           class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition" />
                    <p class="mt-1 text-xs text-tpc-ink/40">PNG / JPG / WEBP, max 5 MB</p>
                    @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                        Caption <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                    </label>
                    <input type="text" name="caption" value="{{ old('caption') }}"
                           class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
                           placeholder="Short caption shown below the image" />
                    @error('caption') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Order --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Display Order <span class="normal-case font-normal text-tpc-ink/40">(leave blank to add at end)</span>
                </label>
                <input type="number" name="order" value="{{ old('order') }}" min="0"
                       class="w-32 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition">
                    Add Section
                </button>
                <a href="{{ route('admin.programs.details.index', $program) }}"
                   class="text-sm font-semibold text-tpc-ink/50 hover:text-tpc-ink transition">Cancel</a>
            </div>
        </form>
    </div>

@endsection
