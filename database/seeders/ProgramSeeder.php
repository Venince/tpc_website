<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['BSIS','Bachelor of Science in Information Systems'],
            ['BSAIS','Bachelor of Science in Accounting Information System'],
            ['BSA','Bachelor of Science in Accountancy'],
            ['BSC','Bachelor of Science in Criminology'],
            ['BAPS','Bachelor of Arts in Political Science'],
            ['BAEL','Bachelor of Arts in English'],
            ['BECED','Bachelor of Early Childhood Education'],
        ];

        foreach ($items as [$code, $name]) {
            Program::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'slug' => Str::slug($code),
                    'description' => 'Short program description here.',
                    'is_active' => true,
                ]
            );
        }
    }
}
