@extends('admin.layout', ['title' => 'Create Service'])

@section('content')
    <x-admin.page-header title="Create service" subtitle="Add a new service to the website."
        :breadcrumbs="['Services' => route('admin.services.index'), 'New' => null]" />

    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.services._form', ['service' => null])

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.services.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Create service</x-admin.button>
        </div>
    </form>
@endsection
