@extends('admin.layout', ['title' => 'Edit Achievement'])

@section('content')
    <x-admin.page-header title="Edit achievement" :subtitle="$service->title"
        :breadcrumbs="['Services' => route('admin.services.index'), $service->title => route('admin.services.show', $service), 'Edit achievement' => null]" />

    <form action="{{ route('admin.services.achievements.update', [$service, $achievement]) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PATCH')
        @include('admin.service-achievements._form', ['achievement' => $achievement])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.services.show', $service)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save changes</x-admin.button>
        </div>
    </form>
@endsection
