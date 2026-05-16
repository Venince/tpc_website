@extends('admin.layout', ['title' => 'Edit Section'])

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
            <div class="flex items-center gap-2.5">
                <h1 class="text-lg font-bold text-tpc-ink">Edit Section</h1>
                @php
                    $typeStyles = ['gallery' => 'bg-purple-50 text-purple-700', 'list' => 'bg-blue-50 text-blue-700', 'text' => 'bg-tpc-primary/8 text-tpc-secondary'];
                @endphp
                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $typeStyles[$detail->type] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $detail->type }}
                </span>
            </div>
            <p class="text-xs text-tpc-ink/40 mt-0.5">Section type cannot be changed after creation.</p>
        </div>
    </div>

    <div class="max-w-xl"
         x-data="{
             items: {{ json_encode(old('items', $detail->items ?? [''])) }},
             addItem() { this.items.push(''); },
             removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); }
         }">

        <form method="POST"
              action="{{ route('admin.programs.details.update', [$program, $detail]) }}"
              enctype="multipart/form-data"
              class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6 space-y-5">
            @csrf @method('PATCH')

            {{-- Heading --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Section Heading <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <input type="text" name="heading" value="{{ old('heading', $detail->heading) }}"
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition"
                       placeholder="e.g. About this Program" />
                @error('heading') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- TEXT --}}
            @if ($detail->type === 'text')
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Body</label>
                    <textarea name="body" rows="6"
                              class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition resize-none">{{ old('body', $detail->body) }}</textarea>
                    @error('body') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif

            {{-- LIST --}}
            @if ($detail->type === 'list')
                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60">Items</label>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-2">
                            <span class="shrink-0 h-1.5 w-1.5 rounded-full bg-tpc-primary"></span>
                            <input type="text" :name="'items[' + index + ']'" x-model="items[index]"
                                   class="flex-1 rounded-xl border border-tpc-primary/20 px-3 py-2 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                            <button type="button" @click="removeItem(index)"
                                    class="shrink-0 h-7 w-7 flex items-center justify-center rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition text-lg leading-none"
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
            @endif

            {{-- GALLERY --}}
            @if ($detail->type === 'gallery')
                <div class="space-y-4">
                    @if ($detail->image_path)
                        <div class="rounded-xl border border-tpc-primary/12 bg-tpc-primary/3 p-3">
                            <p class="text-xs font-semibold text-tpc-ink/60 mb-2">Current image</p>
                            <img src="{{ asset('storage/' . $detail->image_path) }}"
                                 class="w-full max-h-64 rounded-xl border border-tpc-primary/10 object-contain" alt="current">
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                            Replace Image <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                        </label>
                        <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                               class="w-full rounded-xl border border-tpc-primary/20 bg-white px-3 py-2 text-sm
                                      file:mr-3 file:rounded-lg file:border-0 file:bg-tpc-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-tpc-primary
                                      hover:file:bg-tpc-primary/15 transition" />
                        <p class="mt-1.5 text-xs text-tpc-ink/40">PNG / JPG / WEBP · max 5 MB</p>
                        @error('image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                            Caption <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                        </label>
                        <input type="text" name="caption" value="{{ old('caption', $detail->caption) }}"
                               class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                        @error('caption') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- Order --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $detail->order) }}" min="0"
                       class="w-28 rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
            </div>

            <div class="pt-2 border-t border-tpc-primary/8 flex flex-wrap gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('admin.programs.details.index', $program) }}"
                   class="inline-flex items-center rounded-xl border border-tpc-primary/25 bg-white px-5 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
