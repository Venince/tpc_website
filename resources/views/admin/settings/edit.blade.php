@extends('admin.layout', ['title' => 'Settings'])

@section('content')
    <div class="flex items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-semibold text-tpc-ink">Site Settings</h1>
            <p class="mt-1 text-sm text-tpc-ink/70">General website information.</p>
        </div>
    </div>

    <form class="mt-6 rounded-2xl border border-tpc-primary/10 bg-white p-6 shadow-sm"
          method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Site Name</label>
                <input name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
            </div>

            <div class="sm:col-span-2">
                <label class="text-sm font-medium text-tpc-ink">Address</label>
                <input name="address" value="{{ old('address', $settings['address'] ?? '') }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
            </div>

            <div>
                <label class="text-sm font-medium text-tpc-ink">Email</label>
                <input name="email" value="{{ old('email', $settings['email'] ?? '') }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
            </div>

            <div>
                <label class="text-sm font-medium text-tpc-ink">Phone</label>
                <input name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}"
                       class="mt-2 w-full rounded-lg border border-tpc-primary/20 px-3 py-2 focus:border-tpc-primary focus:ring-tpc-primary/20" />
            </div>
        </div>

        <div class="mt-6">
            <button class="rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800">
                Save Settings
            </button>
        </div>
    </form>
@endsection
