{{--
    resources/views/admin/news-posts/_status-badge.blade.php
    Usage: @include('admin.news-posts._status-badge', ['post' => $post])
--}}

@php
    $map = [
        'approved' => ['bg-tpc-accent/30 text-tpc-secondary',  'Approved'],
        'declined' => ['bg-red-100 text-red-700',              'Declined'],
        'pending'  => ['bg-yellow-100 text-yellow-800',        'Pending Review'],
    ];
    [$cls, $label] = $map[$post->status] ?? ['bg-gray-100 text-gray-700', ucfirst($post->status)];
@endphp

<span class="shrink-0 rounded-full px-2 py-1 text-xs font-semibold {{ $cls }}">
    {{ $label }}
</span>
