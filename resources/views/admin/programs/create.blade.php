@extends('admin.layout', ['title' => 'New Program'])

@section('content')
    <x-admin.page-header title="Create program" subtitle="Add a new academic program."
        :breadcrumbs="['Programs' => route('admin.programs.index'), 'New' => null]" />

    <form method="POST" action="{{ route('admin.programs.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.programs._form', ['program' => null])

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.programs.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save program</x-admin.button>
        </div>
    </form>
@endsection
