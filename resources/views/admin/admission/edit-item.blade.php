@extends('admin.layout', ['title' => 'Edit Item'])

@section('content')
    <x-admin.page-header title="Edit item" :subtitle="$item->title"
        :breadcrumbs="['Admission' => route('admin.admission.index'), 'Edit item' => null]" />

    <form method="POST" action="{{ route('admin.admission.sections.items.update', [$section, $item]) }}">
        @csrf @method('PATCH')
        @include('admin.admission._item-fields', ['item' => $item])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.admission.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save changes</x-admin.button>
        </div>
    </form>
@endsection
