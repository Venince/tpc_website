@extends('admin.layout', ['title' => 'Edit Post'])

@section('content')
    <x-admin.page-header title="Edit news post" :subtitle="$newsPost->title"
        :breadcrumbs="['News Posts' => route('admin.news-posts.index'), 'Edit' => null]">
        @include('admin.news-posts._status-badge', ['post' => $newsPost])
    </x-admin.page-header>

    <div class="mb-4">
        @if ($newsPost->isDeclined())
            <x-admin.alert type="error" title="Declined">
                {{ $newsPost->review_note ? 'Reason: ' . $newsPost->review_note : 'No reason provided.' }} Edit and save to re-submit for review.
            </x-admin.alert>
        @elseif ($newsPost->isPending())
            <x-admin.alert type="warning" title="Pending review">Saving will re-submit this post for superadmin approval.</x-admin.alert>
        @elseif ($newsPost->isApproved() && ! auth()->user()->is_super_admin)
            <x-admin.alert type="warning" title="Currently approved and live">Saving changes will un-publish this post and re-submit it for review.</x-admin.alert>
        @endif
    </div>

    <div class="space-y-4">
        {{-- Existing photos live outside the main <form> so each delete can be its own form. --}}
        @php $images = $newsPost->galleryImages; @endphp
        @if ($images->isNotEmpty())
            <x-admin.card title="Current photos" :description="$images->count() . ' photo' . ($images->count() === 1 ? '' : 's')" class="">
                <ul class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
                    @foreach ($images as $image)
                        <li class="relative aspect-square overflow-hidden rounded-lg border border-ui-line bg-ui-bg-2">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="" class="h-full w-full object-cover" loading="lazy">
                            <form method="POST" action="{{ route('admin.news-posts.gallery.destroy', [$newsPost, $image]) }}"
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

        <form method="POST" action="{{ route('admin.news-posts.update', $newsPost) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.news-posts._form', ['newsPost' => $newsPost])

            <div class="ui-actionbar">
                <x-admin.button :href="route('admin.news-posts.index')" variant="secondary">Cancel</x-admin.button>
                <x-admin.button type="submit">{{ auth()->user()->is_super_admin ? 'Save & publish' : 'Save & re-submit' }}</x-admin.button>
            </div>
        </form>
    </div>
@endsection
