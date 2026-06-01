<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Talibon Polytechnic College')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/TPC-Logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/TPC-Logo.png') }}">

    <link rel="preload" as="image" href="{{ asset('images/TPC-Logo.png') }}">

    <script>
        document.documentElement.classList.add('tpc-init');
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans text-tpc-ink bg-white overflow-x-hidden">

    @include('partials.nav')

    <div id="tpc-content" class="relative tpc-prose overflow-x-hidden">
        <main>
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    @stack('scripts')

    <script>
    function handleLike(btn) {
        const postId    = btn.dataset.postId;
        const storageKey = 'tpc_liked_' + postId;
        const countEl   = btn.querySelector('.like-count');
        const iconEl    = btn.querySelector('.like-icon');
        const isLiked   = !!localStorage.getItem(storageKey);
        const current   = parseInt(countEl.textContent);

        if (isLiked) {
            // ── Unlike ──
            countEl.textContent = Math.max(0, current - 1);
            iconEl.setAttribute('fill', 'none');
            iconEl.setAttribute('stroke', 'currentColor');
            iconEl.style.color = '';
            btn.classList.remove('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
            btn.classList.add('text-gray-500', 'border-gray-200');
            localStorage.removeItem(storageKey);

            fetch('{{ url('/news') }}/' + postId + '/unlike', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).catch(() => {
                // Rollback
                countEl.textContent = current;
                iconEl.setAttribute('fill', 'currentColor');
                btn.classList.add('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
                btn.classList.remove('text-gray-500', 'border-gray-200');
                localStorage.setItem(storageKey, '1');
            });

        } else {
            // ── Like ──
            countEl.textContent = current + 1;
            iconEl.setAttribute('fill', 'currentColor');
            iconEl.setAttribute('stroke', 'currentColor');
            btn.classList.add('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
            btn.classList.remove('text-gray-500', 'border-gray-200');
            localStorage.setItem(storageKey, '1');

            fetch('{{ url('/news') }}/' + postId + '/like', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).catch(() => {
                // Rollback
                countEl.textContent = current;
                iconEl.setAttribute('fill', 'none');
                btn.classList.remove('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
                btn.classList.add('text-gray-500', 'border-gray-200');
                localStorage.removeItem(storageKey);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.like-btn').forEach(function (btn) {
            const postId = btn.dataset.postId;
            if (localStorage.getItem('tpc_liked_' + postId)) {
                const iconEl = btn.querySelector('.like-icon');
                iconEl.setAttribute('fill', 'currentColor');
                iconEl.setAttribute('stroke', 'currentColor');
                btn.classList.add('text-tpc-primary', 'border-tpc-primary', 'bg-tpc-primary/10');
                btn.classList.remove('text-gray-500', 'border-gray-200');
            }
        });
    });
    </script>

</body>
</html>
