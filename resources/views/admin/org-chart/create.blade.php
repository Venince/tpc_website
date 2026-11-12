@extends('admin.layout', ['title' => 'Add Person – Org Chart'])

@section('content')
    <x-admin.page-header title="Add person" subtitle="Add someone to the organizational chart."
        :breadcrumbs="['Organizational Chart' => route('admin.org-chart.index'), 'Add person' => null]" />

    <form method="POST" action="{{ route('admin.org-chart.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.org-chart._form', ['node' => null, 'parents' => $parents])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.org-chart.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Add person</x-admin.button>
        </div>
    </form>
@endsection
