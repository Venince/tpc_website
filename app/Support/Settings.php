<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    public static function get(string $key, $default = null): mixed
    {
        return Cache::remember("site_setting:$key", now()->addMinutes(30), function () use ($key, $default) {
            return SiteSetting::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function forget(string $key): void
    {
        Cache::forget("site_setting:$key");
    }
}
