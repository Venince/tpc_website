@extends('admin.layout', ['title' => 'News Review'])

@section('content')
    <x-admin.page-header title="News review queue" subtitle="Approve or decline posts submitted by admins."
        :breadcrumbs="['News Review' => null]" />

    {{-- Status tabs --}}
    <nav aria-label="Review status" class="mb-4 flex flex-wrap gap-2">
        @foreach ([
            'pending'  => ['Pending',  $counts['pending']],
            'approved' => ['Approved', $counts['approved']],
            'declined' => ['Declined', $counts['declined']],
        ] as $key => [$label, $count])
            <a href="{{ route('admin.news-review.index', ['status' => $key]) }}"
               @if ($status === $key) aria-current="page" @endif
               class="inline-flex h-9 items-center gap-2 rounded-lg border px-3.5 text-sm font-medium transition-colors
                      focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2
                      {{ $status === $key ? 'border-brand bg-brand-50 text-brand-dark' : 'border-ui-line bg-white text-ui-muted hover:bg-ui-bg-2 hover:text-ui-ink' }}">
                {{ $label }}
                <span class="rounded-full px-1.5 py-0.5 text-[11px] font-semibold leading-none tabular-nums
                             {{ $status === $key ? 'bg-brand text-white' : 'bg-ui-bg-2 text-ui-muted' }}">{{ $count }}</span>
            </a>
        @endforeach
    </nav>

    <x-admin.card :padding="false">
        @if ($posts->total() > 0)
            <div class="ui-table-wrap">
                <table class="ui-table">
                    <caption class="sr-only">{{ ucfirst($status) }} news posts</caption>
                    <thead>
                        <tr>
                            <th scope="col">Post</th>
                            <th scope="col" class="hidden md:table-cell">Category</th>
                            <th scope="col" class="hidden sm:table-cell">Submitted</th>
                            <th scope="col" class="hidden lg:table-cell">Review note</th>
                            <th scope="col" class="ui-col-actions"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if ($post->image_path)
                                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="" class="ui-thumb !h-12 !w-16" loading="lazy">
                                        @else
                                            <span class="ui-thumb grid !h-12 !w-16 place-items-center text-gray-300"><x-admin.icon name="newspaper" class="h-5 w-5" /></span>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.news-review.show', $post) }}" class="block max-w-[22rem] truncate font-medium text-ui-ink hover:text-brand-dark focus-visible:outline-none focus-visible:underline">{{ $post->title }}</a>
                                            @if ($post->excerpt)<p class="max-w-[22rem] truncate text-xs text-ui-muted">{{ $post->excerpt }}</p>@endif
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell"><x-admin.badge>{{ $post->category }}</x-admin.badge></td>
                                <td class="hidden whitespace-nowrap text-ui-muted sm:table-cell">{{ $post->created_at->format('M d, Y') }}</td>
                                <td class="hidden max-w-[18rem] truncate text-ui-muted lg:table-cell" title="{{ $post->review_note }}">{{ $post->review_note ?? '—' }}</td>
                                <td class="ui-col-actions">
                                    <x-admin.button :href="route('admin.news-review.show', $post)" variant="secondary" size="sm" icon="eye">{{ $status === 'pending' ? 'Review' : 'Preview' }}</x-admin.button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $posts->links('components.admin.pagination') }}
        @else
            <x-admin.empty-state :icon="$status === 'pending' ? 'check-circle' : 'shield-check'" :title="'No ' . $status . ' posts'"
                :description="$status === 'pending' ? 'You are all caught up. New submissions will appear here.' : 'Nothing to show for this status yet.'" />
        @endif
    </x-admin.card>
@endsection
