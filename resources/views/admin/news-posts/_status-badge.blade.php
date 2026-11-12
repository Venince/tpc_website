{{--
    Usage: @include('admin.news-posts._status-badge', ['post' => $post])
--}}
@php
    $map = [
        'approved' => ['success', 'Approved'],
        'declined' => ['danger',  'Declined'],
        'pending'  => ['warning', 'Pending review'],
    ];
    [$type, $label] = $map[$post->status] ?? ['neutral', ucfirst((string) $post->status)];
@endphp
<x-admin.badge :type="$type" dot>{{ $label }}</x-admin.badge>
