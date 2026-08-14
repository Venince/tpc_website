@extends('admin.layout')

@section('title', 'Add Person – Org Chart')

@section('page_actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.org-chart.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-neo-ink/40 hover:text-tpc-primary transition font-medium">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
            Back
        </a>
        <span class="text-neo-ink/20">/</span>
        <h1 class="text-lg font-semibold text-neo-ink">Add Person</h1>
    </div>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.org-chart.store') }}" enctype="multipart/form-data"
      class="max-w-xl mx-auto">
    @csrf

    <div class="rounded-2xl bg-neo-surface shadow-neo p-5 sm:p-6 space-y-5">
        @include('admin.org-chart._form', ['node' => null, 'parents' => $parents])

        <div class="flex justify-end gap-3 pt-2 border-t border-black/[0.06]">
            <a href="{{ route('admin.org-chart.index') }}"
               class="rounded-xl bg-neo-surface shadow-neo-sm px-5 py-2.5 text-sm font-semibold text-neo-ink/60 transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white shadow-neo-sm transition hover:shadow-neo-hover active:shadow-neo-inset-sm">
                Add Person
            </button>
        </div>
    </div>
</form>

@endsection
