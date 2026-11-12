@extends('admin.layout', ['title' => 'Add Achievement'])

@section('content')
    <x-admin.page-header title="Add achievement" :subtitle="'Adding to ' . $program->name"
        :breadcrumbs="['Programs' => route('admin.programs.index'), $program->code => route('admin.programs.show', $program), 'Add achievement' => null]" />

    <form method="POST" action="{{ route('admin.programs.achievements.store', $program) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @include('admin.program-achievements._form', ['achievement' => null])

        <x-admin.card title="Photos" class="">
            <x-admin.multi-image-upload name="photos" label="Photos" :max="10" />
        </x-admin.card>

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.programs.show', $program)" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">Add achievement</x-admin.button>
        </div>
    </form>
@endsection
