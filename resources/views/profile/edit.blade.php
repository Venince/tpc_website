@extends('admin.layout', ['title' => 'My Profile'])

@section('content')

    {{-- ── Profile hero ── --}}
    <div class="relative mb-8 rounded-2xl bg-tpc-primary overflow-hidden">
        {{-- Background pattern --}}
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px),
                                      radial-gradient(circle at 80% 20%, white 1px, transparent 1px);
                    background-size: 40px 40px;"></div>

        <div class="relative px-6 py-8 flex flex-col sm:flex-row sm:items-center gap-5">
            {{-- Avatar --}}
            <div class="shrink-0 h-16 w-16 rounded-2xl bg-white/20 border-2 border-white/30
                        flex items-center justify-center text-white text-2xl font-extrabold backdrop-blur-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold tracking-widest text-tpc-accent uppercase mb-1">My Profile</p>
                <h1 class="text-xl sm:text-2xl font-extrabold text-white truncate">{{ auth()->user()->name }}</h1>
                <p class="text-sm text-white/60 mt-0.5 truncate">{{ auth()->user()->email }}</p>
            </div>

            {{-- Role badge --}}
            <div class="shrink-0">
                @if(auth()->user()->is_super_admin)
                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-tpc-accent/30 border border-tpc-accent/40
                                 px-3 py-1.5 text-xs font-bold text-white">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.745 3.745 0 013.296-1.043A3.745 3.745 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 013.296 1.043 3.745 3.745 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                        </svg>
                        Super Admin
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-white/15 border border-white/20
                                 px-3 py-1.5 text-xs font-bold text-white">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        Admin
                    </span>
                @endif
            </div>
        </div>

        {{-- Wave bottom --}}
        <svg viewBox="0 0 1440 28" fill="none" xmlns="http://www.w3.org/2000/svg"
             preserveAspectRatio="none" class="w-full h-5 sm:h-7 block">
            <path d="M0 28 C360 0 1080 0 1440 28 L1440 28 L0 28 Z" fill="#f9fafb"/>
        </svg>
    </div>

    {{-- ── Quick-nav tabs ── --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1 scrollbar-none">
        <a href="#profile-info"
           class="shrink-0 inline-flex items-center gap-1.5 rounded-xl bg-white border border-tpc-primary/20
                  px-4 py-2 text-xs font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
            </svg>
            Profile Info
        </a>
        <a href="#update-password"
           class="shrink-0 inline-flex items-center gap-1.5 rounded-xl bg-white border border-tpc-primary/20
                  px-4 py-2 text-xs font-bold text-tpc-primary hover:bg-tpc-primary hover:text-white transition">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
            Password
        </a>
        <a href="#delete-account"
           class="shrink-0 inline-flex items-center gap-1.5 rounded-xl bg-white border border-red-200
                  px-4 py-2 text-xs font-bold text-red-500 hover:bg-red-600 hover:text-white hover:border-red-600 transition">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
            </svg>
            Delete Account
        </a>
    </div>

    <div class="max-w-2xl space-y-5">

        {{-- Profile info --}}
        <div id="profile-info" class="scroll-mt-6 rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Password --}}
        <div id="update-password" class="scroll-mt-6 rounded-2xl border border-tpc-primary/10 bg-white shadow-sm overflow-hidden">
            @include('profile.partials.update-password-form')
        </div>

        {{-- Delete account --}}
        <div id="delete-account" class="scroll-mt-6 rounded-2xl border border-red-100 bg-white shadow-sm overflow-hidden">
            @include('profile.partials.delete-user-form')
        </div>

    </div>

@endsection
