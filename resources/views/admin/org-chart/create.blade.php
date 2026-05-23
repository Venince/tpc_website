@extends('admin.layout')

@section('title', 'Add Person – Org Chart')

@section('page_actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.org-chart.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-tpc-primary transition font-medium">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
            Back
        </a>
        <span class="text-gray-300">/</span>
        <h1 class="text-lg font-bold text-gray-900">Add Person</h1>
    </div>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.org-chart.store') }}" enctype="multipart/form-data"
      class="max-w-xl mx-auto space-y-5">
    @csrf

    @include('admin.org-chart._form', ['node' => null, 'parents' => $parents])

    <div class="flex justify-end gap-3 pt-2">
        <a href="{{ route('admin.org-chart.index') }}"
           class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
            Cancel
        </a>
        <button type="submit"
                class="rounded-xl bg-tpc-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-tpc-primary/90 transition">
            Add Person
        </button>
    </div>
</form>

@endsection
