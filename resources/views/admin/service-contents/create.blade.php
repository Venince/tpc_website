@extends('admin.layout', ['title' => 'Add Content Section'])

@section('content')
    <x-admin.page-header title="Add content section" :subtitle="$service->title"
        :breadcrumbs="['Services' => route('admin.services.index'), $service->title => route('admin.services.show', $service), 'Add section' => null]" />

    <form action="{{ route('admin.services.contents.store', $service) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.service-contents._form', ['content' => null])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.services.show', $service)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Add section</x-admin.button>
        </div>
    </form>
@endsection
