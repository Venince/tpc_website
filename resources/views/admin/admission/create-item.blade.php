@extends('admin.layout', ['title' => 'Add Item'])

@section('content')
    <x-admin.page-header title="Add item" :subtitle="'Adding to ' . $section->label"
        :breadcrumbs="['Admission' => route('admin.admission.index'), 'Add item' => null]" />

    <form method="POST" action="{{ route('admin.admission.sections.items.store', $section) }}">
        @csrf
        @include('admin.admission._item-fields', ['item' => null])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.admission.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Add item</x-admin.button>
        </div>
    </form>
@endsection
