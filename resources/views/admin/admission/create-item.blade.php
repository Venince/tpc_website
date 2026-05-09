@extends('admin.layout', ['title' => 'Add Item'])

@section('content')

    <div class="mb-5">
        <a href="{{ route('admin.admission.index') }}"
           class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
            ← Back to Admission
        </a>
    </div>

    <div class="max-w-lg">
        <h2 class="text-base font-bold text-tpc-ink mb-1">Add Item</h2>
        <p class="text-sm text-tpc-ink/50 mb-6">
            Adding to section: <span class="font-semibold text-tpc-ink">{{ $section->label }}</span>
            <span class="ml-2 rounded-full bg-tpc-primary/10 px-2 py-0.5 text-xs font-medium text-tpc-primary">{{ $section->type }}</span>
        </p>

        <form method="POST" action="{{ route('admin.admission.sections.items.store', $section) }}" class="space-y-5">
            @csrf

            {{-- Title --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    @if ($section->type === 'steps') Step Title
                    @elseif ($section->type === 'schedule') Day / Period
                    @else Item Text
                    @endif
                </label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    required
                    autofocus
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
                    placeholder="{{ $section->type === 'schedule' ? 'e.g. Monday – Friday' : ($section->type === 'steps' ? 'e.g. Submit documents' : 'e.g. PSA Birth Certificate') }}"
                />
                @error('title')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Body (for steps and schedule only) --}}
            @if (in_array($section->type, ['steps', 'schedule']))
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                        @if ($section->type === 'steps') Description
                        @else Time / Value
                        @endif
                    </label>
                    <input
                        type="text"
                        name="body"
                        value="{{ old('body') }}"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
                        placeholder="{{ $section->type === 'schedule' ? 'e.g. 8:00 AM – 5:00 PM' : 'Short description of this step.' }}"
                    />
                    @error('body')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-tpc-secondary transition">
                    Add Item
                </button>
                <a href="{{ route('admin.admission.index') }}"
                   class="text-sm font-semibold text-tpc-ink/50 hover:text-tpc-ink transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

@endsection
