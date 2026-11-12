@extends('admin.layout', ['title' => 'Edit Achievement'])

@section('content')
    <x-admin.page-header title="Edit achievement" :subtitle="$achievement->title"
        :breadcrumbs="['Programs' => route('admin.programs.index'), $program->code => route('admin.programs.show', $program), 'Edit achievement' => null]" />

    <div class="space-y-4">
        {{-- Existing photos live outside the main <form>: each delete is its own form (nested forms are invalid HTML). --}}
        @php $images = $achievement->images; @endphp
        @if ($images->isNotEmpty())
            <x-admin.card title="Current photos" :description="$images->count() . ' photo' . ($images->count() === 1 ? '' : 's')" class="">
                <ul class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-5">
                    @foreach ($images as $image)
                        <li class="relative aspect-square overflow-hidden rounded-lg border border-ui-line bg-ui-bg-2">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="" class="h-full w-full object-cover" loading="lazy">
                            <form method="POST" action="{{ route('admin.programs.achievements.images.destroy', [$program, $achievement, $image]) }}"
                                  data-confirm="This photo will be permanently removed." data-confirm-title="Remove this photo?" data-confirm-label="Remove photo"
                                  class="absolute right-1 top-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="grid h-7 w-7 place-items-center rounded-full bg-gray-900/70 text-white transition-colors hover:bg-red-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white" aria-label="Remove photo">
                                    <x-admin.icon name="trash" class="h-3.5 w-3.5" />
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </x-admin.card>
        @endif

        <form method="POST" action="{{ route('admin.programs.achievements.update', [$program, $achievement]) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PATCH')
            @include('admin.program-achievements._form', ['achievement' => $achievement])

            <x-admin.card title="Add more photos" class="">
                <x-admin.multi-image-upload name="photos" label="New photos" :max="10" />
            </x-admin.card>

            <div class="ui-actionbar">
                <x-admin.button :href="route('admin.programs.show', $program)" variant="secondary">Cancel</x-admin.button>
                <x-admin.button type="submit">Save changes</x-admin.button>
            </div>
        </form>
    </div>
@endsection
