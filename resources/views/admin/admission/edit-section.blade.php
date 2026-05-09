@extends('admin.layout', ['title' => 'Edit Section'])

@section('content')

    <div class="mb-5">
        <a href="{{ route('admin.admission.index') }}"
           class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
            ← Back to Admission
        </a>
    </div>

    <div class="max-w-lg">
        <h2 class="text-base font-bold text-tpc-ink mb-1">Edit Section</h2>
        <p class="text-sm text-tpc-ink/50 mb-6">
            Key: <span class="font-mono text-tpc-primary">{{ $section->key }}</span> &middot;
            Type: <span class="font-medium">{{ $section->type }}</span>
        </p>

        <form method="POST" action="{{ route('admin.admission.sections.update', $section) }}" class="space-y-5">
            @csrf @method('PATCH')

            {{-- Label --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Heading Label
                </label>
                <input
                    type="text"
                    name="label"
                    value="{{ old('label', $section->label) }}"
                    required
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
                    placeholder="e.g. For Freshmen"
                />
                @error('label')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Note / Tip --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    Note / Tip
                    <span class="normal-case font-normal text-tpc-ink/40 ml-1">(optional — shown as callout below items)</span>
                </label>
                <textarea
                    name="note"
                    rows="3"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition resize-none"
                    placeholder="Leave blank to hide the callout box."
                >{{ old('note', $section->note) }}</textarea>
                @error('note')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Visibility --}}
            <div class="flex items-center gap-3">
                <input
                    type="checkbox"
                    name="is_visible"
                    id="is_visible"
                    value="1"
                    {{ old('is_visible', $section->is_visible) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-gray-300 text-tpc-primary focus:ring-tpc-primary/30"
                />
                <label for="is_visible" class="text-sm font-medium text-tpc-ink">
                    Visible on public page
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.admission.index') }}"
                   class="text-sm font-semibold text-tpc-ink/50 hover:text-tpc-ink transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
