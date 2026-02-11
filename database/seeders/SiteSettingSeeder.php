<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name' => 'Talibon Polytechnic College',
            'address' => 'Talibon, Bohol, Philippines',
            'email' => 'info@tpc.edu.ph',
            'phone' => '+63 000 000 0000',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
