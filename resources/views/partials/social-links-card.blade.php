@php
    $platformMeta = [
        'facebook'  => ['label' => 'Facebook',  'color' => 'text-[#1877F2]', 'bg' => 'bg-[#1877F2]/8 hover:bg-[#1877F2]/15', 'ring' => 'ring-[#1877F2]/20', 'icon' => 'M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z'],
        'instagram' => ['label' => 'Instagram', 'color' => 'text-[#E1306C]', 'bg' => 'bg-[#E1306C]/8 hover:bg-[#E1306C]/15', 'ring' => 'ring-[#E1306C]/20', 'icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z'],
        'twitter'   => ['label' => 'X (Twitter)', 'color' => 'text-gray-900', 'bg' => 'bg-gray-100 hover:bg-gray-200', 'ring' => 'ring-gray-200', 'icon' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.213 5.567zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
        'youtube'   => ['label' => 'YouTube',   'color' => 'text-[#FF0000]', 'bg' => 'bg-[#FF0000]/8 hover:bg-[#FF0000]/15', 'ring' => 'ring-[#FF0000]/20', 'icon' => 'M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58zM9.75 15.02V8.98L15.5 12z'],
        'tiktok'    => ['label' => 'TikTok',    'color' => 'text-gray-900', 'bg' => 'bg-gray-100 hover:bg-gray-200', 'ring' => 'ring-gray-200', 'icon' => 'M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.3 6.3 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z'],
        'linkedin'  => ['label' => 'LinkedIn',  'color' => 'text-[#0A66C2]', 'bg' => 'bg-[#0A66C2]/8 hover:bg-[#0A66C2]/15', 'ring' => 'ring-[#0A66C2]/20', 'icon' => 'M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2zM4 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4z'],
        'other'     => ['label' => 'Link',      'color' => 'text-tpc-primary', 'bg' => 'bg-tpc-primary/8 hover:bg-tpc-primary/15', 'ring' => 'ring-tpc-primary/20', 'icon' => 'M13.828 10.172a4 4 0 0 0-5.656 0l-4 4a4 4 0 1 0 5.656 5.656l1.102-1.101m-.758-4.899a4 4 0 0 0 5.656 0l4-4a4 4 0 0 0-5.656-5.656l-1.1 1.1'],
    ];
@endphp

@if (!empty($links))
    <div class="bg-white rounded-2xl border border-gray-300 shadow-md overflow-hidden">
        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100">
            <p class="text-xs font-bold text-tpc-primary uppercase tracking-widest">Follow Us</p>
        </div>
        <div class="p-4 sm:p-5 space-y-2.5">
            @foreach ($links as $link)
                @php
                    $meta = $platformMeta[$link['platform'] ?? 'other'] ?? $platformMeta['other'];
                    $displayLabel = !empty($link['label']) ? $link['label'] : $meta['label'];
                @endphp
                <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                   class="group flex items-center gap-3 rounded-2xl px-4 py-3 ring-1 transition {{ $meta['bg'] }} {{ $meta['ring'] }}">
                    <div class="shrink-0 h-9 w-9 rounded-xl bg-white shadow-sm flex items-center justify-center ring-1 ring-gray-100">
                        <svg class="h-4 w-4 {{ $meta['color'] }}" viewBox="0 0 24 24" fill="currentColor">
                            <path d="{{ $meta['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ $displayLabel }}</p>
                        <p class="text-[11px] text-gray-400 truncate">{{ parse_url($link['url'], PHP_URL_HOST) }}</p>
                    </div>
                    <svg class="h-4 w-4 text-gray-300 group-hover:text-gray-500 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                </a>
            @endforeach
        </div>
    </div>
@endif
