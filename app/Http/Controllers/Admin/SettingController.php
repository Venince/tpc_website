<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private array $keys = ['site_name','address','email','phone'];

    public function edit()
    {
        $settings = SiteSetting::whereIn('key', $this->keys)->pluck('value', 'key');

        return view('admin.settings.edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required','string','max:255'],
            'address' => ['nullable','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'phone' => ['nullable','string','max:50'],
        ]);

        foreach ($this->keys as $key) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $data[$key] ?? null]
            );
        }

        foreach (['site_name','address','email','phone'] as $key) {
            \App\Support\Settings::forget($key);
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Settings saved successfully.');

    }
}
