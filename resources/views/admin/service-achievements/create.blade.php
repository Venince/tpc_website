@extends('admin.layout', ['title' => 'Add Achievement'])

@section('content')
    <x-admin.page-header title="Add achievement" :subtitle="$service->title"
        :breadcrumbs="['Services' => route('admin.services.index'), $service->title => route('admin.services.show', $service), 'Add achievement' => null]" />

    <form action="{{ route('admin.services.achievements.store', $service) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.service-achievements._form', ['achievement' => null])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.services.show', $service)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Add achievement</x-admin.button>
        </div>
    </form>
@endsection
