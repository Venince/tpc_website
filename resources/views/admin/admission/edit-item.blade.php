@extends('admin.layout', ['title' => 'Edit Item'])

@section('content')

    <div class="mb-5">
        <a href="{{ route('admin.admission.index') }}"
           class="text-sm font-semibold text-tpc-primary hover:text-tpc-secondary transition">
            ← Back to Admission
        </a>
    </div>

    <div class="max-w-lg">
        <h2 class="text-base font-bold text-tpc-ink mb-1">Edit Item</h2>
        <p class="text-sm text-tpc-ink/50 mb-6">
            Section: <span class="font-semibold text-tpc-ink">{{ $section->label }}</span>
            <span class="ml-2 rounded-full bg-tpc-primary/10 px-2 py-0.5 text-xs font-medium text-tpc-primary">{{ $section->type }}</span>
        </p>

        <form method="POST" action="{{ route('admin.admission.sections.items.update', [$section, $item]) }}" class="space-y-5">
            @csrf @method('PATCH')

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
                    value="{{ old('title', $item->title) }}"
                    required
                    autofocus
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
                />
                @error('title')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Body --}}
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
                        value="{{ old('body', $item->body) }}"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/20 outline-none transition"
                    />
                    @error('body')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

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
