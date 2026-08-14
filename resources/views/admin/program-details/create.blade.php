@extends('admin.layout', ['title' => 'Add Section'])

@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <a href="{{ route('admin.programs.details.index', $program) }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition mb-2">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ $program->code }} Details
            </a>
            <h1 class="text-lg font-semibold text-neo-ink">Add Section</h1>
            <p class="text-xs text-neo-ink/45 mt-0.5">For: <span class="font-semibold text-neo-ink/70">{{ $program->name }}</span></p>
        </div>
    </div>

    <div class="max-w-xl"
         x-data="{
             type: '{{ old('type', 'text') }}',
             items: {{ json_encode(old('items', [''])) }},
             addItem() { this.items.push(''); },
             removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); }
         }">

        <form method="POST"
              action="{{ route('admin.programs.details.store', $program) }}"
              enctype="multipart/form-data"
              class="rounded-2xl bg-neo-surface shadow-neo p-5 sm:p-6 space-y-5">
            @csrf

            {{-- Type selector --}}
            <div>
                <label class="block text-sm font-medium text-neo-ink/70 mb-2">Section Type</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach (['text' => 'Text Block', 'list' => 'Bullet List', 'gallery' => 'Gallery Image'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="{{ $val }}" x-model="type" class="sr-only" />
                            <span class="block rounded-xl px-3 py-2.5 text-center text-xs font-semibold transition"
                                  :class="type === '{{ $val }}'
                                      ? 'shadow-neo-inset-sm bg-neo-bg text-tpc-primary'
                                      : 'shadow-neo-sm bg-neo-surface text-neo-ink/55 hover:shadow-neo-hover'">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('type') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Heading --}}
            <div>
                <label class="block text-sm font-medium text-neo-ink/70 mb-1.5">
                    Section Heading <span class="text-neo-ink/35 font-normal">(optional)</span>
                </label>
                <input type="text" name="heading" value="{{ old('heading') }}"
                       class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-3 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition"
                       placeholder="e.g. About this Program, Career Opportunities" />
                @error('heading') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- TEXT --}}
            <div x-show="type === 'text'" x-cloak>
                <label class="block text-sm font-medium text-neo-ink/70 mb-1.5">Body</label>
                <textarea name="body" rows="6"
                          class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-3 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition resize-none"
                          placeholder="Write paragraph content here...">{{ old('body') }}</textarea>
                @error('body') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- LIST --}}
            <div x-show="type === 'list'" x-cloak class="space-y-3">
                <label class="block text-sm font-medium text-neo-ink/70">Items</label>
                <template x-for="(item, index) in items" :key="index">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0 h-1.5 w-1.5 rounded-full bg-tpc-primary"></span>
                        <input type="text" :name="'items[' + index + ']'" x-model="items[index]"
                               class="flex-1 rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-3 py-2 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition"
                               placeholder="Enter item text" />
                        <button type="button" @click="removeItem(index)"
                                class="shrink-0 h-7 w-7 flex items-center justify-center rounded-lg bg-neo-surface shadow-neo-sm text-red-400 hover:text-red-600 hover:shadow-neo-hover active:shadow-neo-inset-sm transition text-lg leading-none"
                                title="Remove">×</button>
                    </div>
                </template>
                <button type="button" @click="addItem()"
                        class="inline-flex items-center gap-1 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Item
                </button>
                @error('items') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- GALLERY --}}
            <div x-show="type === 'gallery'" x-cloak class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neo-ink/70 mb-1.5">Image</label>
                    <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                           class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-3 py-2 text-sm text-neo-ink
                                  file:mr-3 file:rounded-lg file:border-0 file:bg-tpc-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-tpc-primary
                                  hover:file:bg-tpc-primary/15 transition" />
                    <p class="mt-1.5 text-xs text-neo-ink/35">PNG / JPG / WEBP · max 5 MB</p>
                    @error('image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neo-ink/70 mb-1.5">
                        Caption <span class="text-neo-ink/35 font-normal">(optional)</span>
                    </label>
                    <input type="text" name="caption" value="{{ old('caption') }}"
                           class="w-full rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-3 py-2.5 text-sm text-neo-ink placeholder-neo-ink/30 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition"
                           placeholder="Short caption shown below the image" />
                    @error('caption') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Order --}}
            <div>
                <label class="block text-sm font-medium text-neo-ink/70 mb-1.5">
                    Display Order <span class="text-neo-ink/35 font-normal">(leave blank to add at end)</span>
                </label>
                <input type="number" name="order" value="{{ old('order') }}" min="0"
                       class="w-28 rounded-xl bg-neo-bg shadow-neo-inset-sm border-0 px-3 py-2.5 text-sm text-neo-ink focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 transition" />
            </div>

            <div class="pt-2 border-t border-black/[0.06] flex flex-wrap gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white
                               shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Add Section
                </button>
                <a href="{{ route('admin.programs.details.index', $program) }}"
                   class="inline-flex items-center rounded-xl bg-neo-surface shadow-neo-sm px-5 py-2.5 text-sm font-semibold text-tpc-primary
                          transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
