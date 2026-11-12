@extends('admin.layout', ['title' => 'Edit About Slide'])

@section('content')
    <x-admin.page-header title="Edit slide" :subtitle="$aboutSlide->title ?: 'Slide #' . $aboutSlide->sort_order"
        :breadcrumbs="['About Slides' => route('admin.about-slides.index'), 'Edit' => null]" />

    <form action="{{ route('admin.about-slides.update', $aboutSlide) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.about-slides._form', ['aboutSlide' => $aboutSlide])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.about-slides.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Update slide</x-admin.button>
        </div>
    </form>
@endsection
