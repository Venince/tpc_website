@extends('admin.layout', ['title' => 'Edit Section'])

@section('content')

    {{-- ── Page header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <a href="{{ route('admin.admission.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-tpc-primary hover:text-tpc-secondary transition mb-2">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Admission
            </a>
            <h1 class="text-lg font-bold text-tpc-ink">Edit Section</h1>
        </div>
    </div>

    <div class="max-w-lg">
        {{-- Section meta context --}}
        <div class="mb-5 flex items-center gap-2.5 rounded-xl border border-tpc-primary/12 bg-tpc-primary/4 px-4 py-3">
            <svg class="h-4 w-4 text-tpc-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
            <p class="text-xs text-tpc-ink/60">
                Key: <span class="font-mono font-semibold text-tpc-primary">{{ $section->key }}</span>
                &middot; Type: <span class="font-semibold text-tpc-ink/80">{{ $section->type }}</span>
            </p>
        </div>

        <form method="POST" action="{{ route('admin.admission.sections.update', $section) }}"
              class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6 space-y-5">
            @csrf @method('PATCH')

            {{-- Label --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">Heading Label</label>
                <input type="text" name="label" value="{{ old('label', $section->label) }}" required
                       placeholder="e.g. For Freshmen"
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition placeholder:text-tpc-ink/30" />
                @error('label') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Note / Tip --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Note / Tip
                    <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                </label>
                <textarea name="note" rows="3"
                          placeholder="Leave blank to hide the callout box."
                          class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition resize-none placeholder:text-tpc-ink/30">{{ old('note', $section->note) }}</textarea>
                <p class="mt-1.5 text-xs text-tpc-ink/40">Shown as a callout below the section items.</p>
                @error('note') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Visibility toggle --}}
            <div class="flex items-center gap-3 rounded-xl border border-tpc-primary/15 bg-tpc-primary/[0.03] px-4 py-3">
                <input type="checkbox" name="is_visible" id="is_visible" value="1"
                       {{ old('is_visible', $section->is_visible) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-tpc-primary/30 text-tpc-primary focus:ring-tpc-primary/30 cursor-pointer" />
                <label for="is_visible" class="flex-1 cursor-pointer select-none">
                    <span class="text-sm font-semibold text-tpc-ink">Visible on public page</span>
                    <p class="text-xs text-tpc-ink/50 mt-0.5">When unchecked, this section is hidden from visitors.</p>
                </label>
            </div>

            <div class="pt-2 border-t border-tpc-primary/8 flex flex-wrap gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-tpc-secondary transition focus:outline-none focus:ring-2 focus:ring-tpc-primary/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('admin.admission.index') }}"
                   class="inline-flex items-center rounded-xl border border-tpc-primary/25 bg-white px-5 py-2.5 text-sm font-semibold text-tpc-primary hover:bg-tpc-primary/5 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
