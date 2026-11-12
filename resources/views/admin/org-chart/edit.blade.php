@extends('admin.layout', ['title' => 'Edit – ' . $orgChart->name])

@section('content')
    <x-admin.page-header title="Edit person" :subtitle="$orgChart->name"
        :breadcrumbs="['Organizational Chart' => route('admin.org-chart.index'), $orgChart->name => null]" />

    <form method="POST" action="{{ route('admin.org-chart.update', $orgChart) }}" enctype="multipart/form-data">
        @csrf @method('PATCH')
        @include('admin.org-chart._form', ['node' => $orgChart, 'parents' => $parents])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.org-chart.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save changes</x-admin.button>
        </div>
    </form>
@endsection
