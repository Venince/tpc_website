{{--
    resources/views/admin/news-posts/_status-badge.blade.php
    Usage: @include('admin.news-posts._status-badge', ['post' => $post])
--}}

@php
    $map = [
        'approved' => ['bg-emerald-50 text-emerald-700',  'Approved'],
        'declined' => ['bg-red-50 text-red-700',          'Declined'],
        'pending'  => ['bg-amber-50 text-amber-700',      'Pending Review'],
    ];
    [$cls, $label] = $map[$post->status] ?? ['bg-neo-bg text-neo-ink/50', ucfirst($post->status)];
@endphp

<span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $cls }}">
    {{ $label }}
</span>
