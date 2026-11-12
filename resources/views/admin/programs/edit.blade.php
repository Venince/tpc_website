@extends('admin.layout', ['title' => 'Edit Program'])

@section('content')
    <x-admin.page-header title="Edit program" :subtitle="'Update information for ' . $program->code . '.'"
        :breadcrumbs="['Programs' => route('admin.programs.index'), $program->code => route('admin.programs.show', $program), 'Edit' => null]">
        <x-admin.button :href="route('admin.programs.show', $program)" variant="secondary" icon="users">People &amp; achievements</x-admin.button>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.programs.update', $program) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.programs._form', ['program' => $program])

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.programs.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Update program</x-admin.button>
        </div>
    </form>
@endsection
