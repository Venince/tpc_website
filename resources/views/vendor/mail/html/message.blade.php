<x-mail::layout>
@php
    $logoCid = null;
    $logoPath = public_path('images/TPC-Logo.png');

    // Laravel/Symfony may expose the message object as $message or $mailerMessage
    $mailObj = $message ?? $mailerMessage ?? null;

    if ($mailObj && file_exists($logoPath)) {
        // SwiftMailer-style wrapper
        if (method_exists($mailObj, 'embed')) {
            $logoCid = $mailObj->embed($logoPath);
        }
        // Symfony Mailer style
        elseif (method_exists($mailObj, 'embedFromPath')) {
            $logoCid = $mailObj->embedFromPath($logoPath);
        }
    }
@endphp

{{-- Header --}}
<x-slot:header>
    <x-mail::header :url="config('app.url')" :logo="$logoCid">
        Talibon Polytechnic College
    </x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
    <x-mail::subcopy>
        {!! $subcopy !!}
    </x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
    <x-mail::footer>
        © {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
    </x-mail::footer>
</x-slot:footer>
</x-mail::layout>
