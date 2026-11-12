@extends('admin.layout', ['title' => 'Add Person'])

@section('content')
    <x-admin.page-header title="Add person" :subtitle="'Adding to ' . $program->name"
        :breadcrumbs="['Programs' => route('admin.programs.index'), $program->code => route('admin.programs.show', $program), 'Add person' => null]" />

    <form method="POST" action="{{ route('admin.programs.people.store', $program) }}" enctype="multipart/form-data">
        @csrf
        @include('admin.program-people._form', ['person' => null])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.programs.show', $program)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Add person</x-admin.button>
        </div>
    </form>
@endsection
