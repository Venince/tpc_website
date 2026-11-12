@extends('admin.layout', ['title' => 'Review Post'])

@section('content')
    <x-admin.page-header title="Review post" :subtitle="$newsPost->title"
        :breadcrumbs="['News Review' => route('admin.news-review.index'), 'Review' => null]">
        @include('admin.news-posts._status-badge', ['post' => $newsPost])
        @if ($newsPost->reviewed_at)
            <span class="text-xs text-ui-muted">{{ $newsPost->reviewed_at->format('M d, Y') }}@if ($newsPost->reviewer) · {{ $newsPost->reviewer->name }}@endif</span>
        @endif
    </x-admin.page-header>

    <div class="grid items-start gap-4 lg:grid-cols-3">
        {{-- Post preview --}}
        <x-admin.card :padding="false" class="overflow-hidden lg:col-span-2">
            @if ($newsPost->image_path)
                <img src="{{ asset('storage/' . $newsPost->image_path) }}" alt="{{ $newsPost->title }}" class="max-h-[28rem] w-full bg-ui-bg-2 object-contain">
            @endif
            <article class="p-5 sm:p-6">
                <div class="flex flex-wrap items-center gap-2 text-xs text-ui-muted">
                    <x-admin.badge type="success">{{ $newsPost->category }}</x-admin.badge>
                    <span>Submitted {{ $newsPost->created_at->format('F d, Y') }}</span>
                </div>
                <h3 class="mt-3 text-xl font-semibold leading-snug text-ui-ink">{{ $newsPost->title }}</h3>
                @if ($newsPost->excerpt)
                    <p class="mt-2 text-sm italic text-ui-muted">{{ $newsPost->excerpt }}</p>
                @endif
                <div class="ui-prose mt-4 break-words">{!! nl2br(e($newsPost->body)) !!}</div>

                @if ($newsPost->galleryImages->isNotEmpty())
                    <div class="mt-6 border-t border-ui-line pt-4">
                        <p class="mb-3 text-xs font-medium uppercase tracking-wide text-ui-muted">Photos ({{ $newsPost->galleryImages->count() }})</p>
                        <ul class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                            @foreach ($newsPost->galleryImages as $img)
                                <li class="aspect-[4/3] overflow-hidden rounded-lg border border-ui-line bg-ui-bg-2">
                                    <img src="{{ asset('storage/' . $img->image_path) }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </article>
        </x-admin.card>

        {{-- Decision panel --}}
        <div class="space-y-4 lg:sticky lg:top-0">
            @if (! $newsPost->isApproved())
                <x-admin.card title="Approve & publish" description="This post becomes visible to the public immediately.">
                    <form method="POST" action="{{ route('admin.news-review.approve', $newsPost) }}"
                          data-confirm="“{{ $newsPost->title }}” will go live on the website." data-confirm-title="Approve and publish this post?" data-confirm-label="Approve & publish" data-confirm-danger="false">
                        @csrf
                        <x-admin.button type="submit" icon="check" class="ui-btn-block">Approve &amp; publish</x-admin.button>
                    </form>
                </x-admin.card>
            @endif

            @if (! $newsPost->isDeclined())
                <x-admin.card title="Decline" description="Optionally add a note so the author knows what to fix.">
                    <form method="POST" action="{{ route('admin.news-review.decline', $newsPost) }}"
                          data-confirm="The author will see your note and can edit and re-submit." data-confirm-title="Decline this post?" data-confirm-label="Decline post" class="space-y-3">
                        @csrf
                        <x-admin.field label="Reason" name="review_note">
                            <textarea id="review_note" name="review_note" rows="3" maxlength="1000" placeholder="Reason for declining (optional)…"
                                      class="ui-textarea @error('review_note') is-invalid @enderror">{{ old('review_note', $newsPost->review_note) }}</textarea>
                        </x-admin.field>
                        <x-admin.button type="submit" variant="danger" class="ui-btn-block">Decline post</x-admin.button>
                    </form>
                </x-admin.card>
            @endif

            @if (! $newsPost->isPending())
                <x-admin.card title="Reset to pending" description="Move this post back to the review queue.">
                    <form method="POST" action="{{ route('admin.news-review.pending', $newsPost) }}"
                          data-confirm="It will be unpublished and returned to the queue." data-confirm-title="Reset this post to pending?" data-confirm-label="Reset to pending" data-confirm-danger="false">
                        @csrf
                        <x-admin.button type="submit" variant="secondary" icon="arrow-path" class="ui-btn-block">Reset to pending</x-admin.button>
                    </form>
                </x-admin.card>
            @endif

            <x-admin.card title="Post details">
                <dl class="space-y-3 text-sm">
                    @foreach ([
                        ['Slug', $newsPost->slug],
                        ['Created', $newsPost->created_at->format('M d, Y g:i A')],
                        ['Updated', $newsPost->updated_at->format('M d, Y g:i A')],
                        ['Published', $newsPost->published_at?->format('M d, Y g:i A')],
                    ] as [$k, $v])
                        @if ($v)
                            <div class="flex items-baseline justify-between gap-4">
                                <dt class="shrink-0 text-ui-muted">{{ $k }}</dt>
                                <dd class="min-w-0 break-all text-right text-ui-ink">{{ $v }}</dd>
                            </div>
                        @endif
                    @endforeach
                </dl>
            </x-admin.card>
        </div>
    </div>
@endsection
