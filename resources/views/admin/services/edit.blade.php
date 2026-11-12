@extends('admin.layout', ['title' => 'Edit Service'])

@section('content')
    <x-admin.page-header title="Edit service" :subtitle="$service->title"
        :breadcrumbs="['Services' => route('admin.services.index'), $service->title => route('admin.services.show', $service), 'Edit' => null]" />

    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PATCH')
        @include('admin.services._form', ['service' => $service])

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.services.show', $service)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save changes</x-admin.button>
        </div>
    </form>
@endsection
