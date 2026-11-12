@extends('admin.layout', ['title' => 'Edit Content Section'])

@section('content')
    <x-admin.page-header title="Edit content section" :subtitle="$service->title"
        :breadcrumbs="['Services' => route('admin.services.index'), $service->title => route('admin.services.show', $service), 'Edit section' => null]" />

    <form action="{{ route('admin.services.contents.update', [$service, $content]) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PATCH')
        @include('admin.service-contents._form', ['content' => $content])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.services.show', $service)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save changes</x-admin.button>
        </div>
    </form>
@endsection
