@extends('admin.layout', ['title' => 'Edit Person'])

@section('content')
    <x-admin.page-header title="Edit person" :subtitle="$person->name"
        :breadcrumbs="['Programs' => route('admin.programs.index'), $program->code => route('admin.programs.show', $program), 'Edit person' => null]" />

    <form method="POST" action="{{ route('admin.programs.people.update', [$program, $person]) }}" enctype="multipart/form-data">
        @csrf @method('PATCH')
        @include('admin.program-people._form', ['person' => $person])
        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.programs.show', $program)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Save changes</x-admin.button>
        </div>
    </form>
@endsection
