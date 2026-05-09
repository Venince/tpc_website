@extends('admin.layout', ['title' => 'Edit Section'])

@section('content')

    <div class="mb-5">
        <a href="{{ route('admin.programs.details.index', $program) }}"
           class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
            ← Back to {{ $program->code }} Details
        </a>
    </div>

    <div class="max-w-xl"
         x-data="{
            items: {{ json_encode(old('items', $detail->items ?? [''])) }},
            addItem() { this.items.push(''); },
            removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); }
         }">

        <h2 class="text-base font-bold text-tpc-ink mb-1">Edit Section</h2>
        <p class="text-sm text-tpc-ink/50 mb-1">
            Type: <span class="rounded-full px-2 py-0.5 text-xs font-bold uppercase
                {{ $detail->type === 'gallery' ? 'bg-purple-100 text-purple-700' : ($detail->type === 'list' ? 'bg-blue-100 text-blue-700' : 'bg-tpc-accent/30 text-tpc-secondary') }}">
                {{ $detail->type }}
            </span>
        </p>
        <p class="text-xs text-tpc-ink/40 mb-6">Section type cannot be changed after creation.</p>

        <form method="POST"
              action="{{ route('admin.programs.details.update', [$program, $detail]) }}"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf @method('PATCH')

            {{-- Heading --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Section Heading <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <input type="text" name="heading" value="{{ old('heading', $detail->heading) }}"
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
                       placeholder="e.g. About this Program" />
                @error('heading') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- TEXT --}}
            @if ($detail->type === 'text')
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Body</label>
                    <textarea name="body" rows="6"
                              class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition resize-y">{{ old('body', $detail->body) }}</textarea>
                    @error('body') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif

            {{-- LIST --}}
            @if ($detail->type === 'list')
                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60">Items</label>

                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-2">
                            <span class="shrink-0 h-1.5 w-1.5 rounded-full bg-tpc-primary mt-0.5"></span>
                            <input type="text" :name="'items[' + index + ']'" x-model="items[index]"
                                   class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition" />
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
            @endif

            {{-- GALLERY --}}
            @if ($detail->type === 'gallery')
                <div class="space-y-4">
                    @if ($detail->image_path)
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-2">Current Image</label>
                            <img src="{{ asset('storage/' . $detail->image_path) }}"
                                 class="h-40 w-auto rounded-xl border border-gray-200 object-cover" alt="current">
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                            Replace Image <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                        </label>
                        <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                               class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition" />
                        <p class="mt-1 text-xs text-tpc-ink/40">PNG / JPG / WEBP, max 5 MB</p>
                        @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                            Caption <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                        </label>
                        <input type="text" name="caption" value="{{ old('caption', $detail->caption) }}"
                               class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition" />
                        @error('caption') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- Order --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $detail->order) }}" min="0"
                       class="w-32 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.programs.details.index', $program) }}"
                   class="text-sm font-semibold text-tpc-ink/50 hover:text-tpc-ink transition">Cancel</a>
            </div>
        </form>
    </div>

@endsection
