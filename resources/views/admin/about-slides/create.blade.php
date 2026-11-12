@extends('admin.layout', ['title' => 'Add About Slide'])

@section('content')
    <x-admin.page-header title="Add new slide" subtitle="Add an image to the homepage About carousel."
        :breadcrumbs="['About Slides' => route('admin.about-slides.index'), 'New' => null]" />

    <form action="{{ route('admin.about-slides.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.about-slides._form', ['aboutSlide' => null])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.about-slides.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save slide</x-admin.button>
        </div>
    </form>
@endsection
