@extends('admin.layout', ['title' => 'Edit Item'])

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
            <h1 class="text-lg font-bold text-tpc-ink">Edit Item</h1>
        </div>
    </div>

    <div class="max-w-lg">
        {{-- Section context --}}
        <div class="mb-5 flex items-center gap-2.5 rounded-xl border border-tpc-primary/12 bg-tpc-primary/4 px-4 py-3">
            <svg class="h-4 w-4 text-tpc-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
            </svg>
            <div class="text-xs">
                <span class="text-tpc-ink/50">Section:</span>
                <span class="font-semibold text-tpc-ink ml-1">{{ $section->label }}</span>
                <span class="ml-2 inline-flex items-center rounded-full bg-tpc-primary/10 px-2 py-0.5 text-[10px] font-semibold text-tpc-primary">{{ $section->type }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.admission.sections.items.update', [$section, $item]) }}"
              class="rounded-2xl border border-tpc-primary/10 bg-white shadow-sm p-5 sm:p-6 space-y-5">
            @csrf @method('PATCH')

            {{-- Title --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                    @if ($section->type === 'steps') Step Title
                    @elseif ($section->type === 'schedule') Day / Period
                    @else Item Text
                    @endif
                </label>
                <input type="text" name="title" value="{{ old('title', $item->title) }}" required autofocus
                       class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                @error('title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Body --}}
            @if (in_array($section->type, ['steps', 'schedule']))
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-tpc-ink/60 mb-1.5">
                        @if ($section->type === 'steps') Description
                        @else Time / Value
                        @endif
                        <span class="normal-case font-normal text-tpc-ink/40">(optional)</span>
                    </label>
                    <input type="text" name="body" value="{{ old('body', $item->body) }}"
                           class="w-full rounded-xl border border-tpc-primary/20 px-3 py-2.5 text-sm focus:border-tpc-primary focus:ring-2 focus:ring-tpc-primary/15 outline-none transition" />
                    @error('body') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif

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
