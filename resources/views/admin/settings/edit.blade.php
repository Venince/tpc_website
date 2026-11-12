@extends('admin.layout', ['title' => 'Settings'])

@section('content')
    <x-admin.page-header title="Site settings" subtitle="General website information shown across public pages."
        :breadcrumbs="['Settings' => null]" />

    <form method="POST" action="{{ route('admin.settings.update') }}" class=" space-y-4">
        @csrf

        <x-admin.card title="Site identity" description="Displayed in the browser tab, header and footer.">
            <x-admin.field label="Site name" name="site_name" required>
                <input id="site_name" type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                       placeholder="e.g. Talibon Polytechnic College" required class="ui-input @error('site_name') is-invalid @enderror">
            </x-admin.field>
        </x-admin.card>

        <x-admin.card title="Location & contact" description="Shown in the footer and on the contact page.">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.field label="Address" name="address" class="sm:col-span-2">
                    <input id="address" type="text" name="address" value="{{ old('address', $settings['address'] ?? '') }}"
                           placeholder="e.g. Poblacion, Talibon, Bohol" class="ui-input @error('address') is-invalid @enderror">
                </x-admin.field>
                <x-admin.field label="Email" name="email">
                    <input id="email" type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}"
                           placeholder="info@school.edu.ph" class="ui-input @error('email') is-invalid @enderror">
                </x-admin.field>
                <x-admin.field label="Phone" name="phone">
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}"
                           placeholder="+63 32 000 0000" class="ui-input @error('phone') is-invalid @enderror">
                </x-admin.field>
            </div>
        </x-admin.card>

        <div class="ui-actionbar">
            <button type="reset" class="ui-btn ui-btn-secondary">Reset</button>
            <x-admin.button type="submit">Save settings</x-admin.button>
        </div>
    </form>
@endsection
