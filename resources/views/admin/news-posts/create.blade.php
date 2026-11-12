@extends('admin.layout', ['title' => 'New Post'])

@section('content')
    <x-admin.page-header title="Create news post" subtitle="Share an announcement, event, advisory or scholarship."
        :breadcrumbs="['News Posts' => route('admin.news-posts.index'), 'New' => null]" />

    @unless (auth()->user()->is_super_admin)
        <x-admin.alert type="warning" class="mb-4">
            <strong class="font-semibold">Heads up:</strong> after saving, this post is sent to the superadmin for review before it goes live.
        </x-admin.alert>
    @endunless

    <form method="POST" action="{{ route('admin.news-posts.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.news-posts._form', ['newsPost' => null])

        <div class="ui-actionbar">
            <x-admin.button :href="route('admin.news-posts.index')" variant="secondary">Cancel</x-admin.button>
            <x-admin.button type="submit">{{ auth()->user()->is_super_admin ? 'Publish post' : 'Submit for review' }}</x-admin.button>
        </div>
    </form>
@endsection
