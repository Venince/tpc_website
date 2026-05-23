<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrgChartNode;

class OrgChartSeeder extends Seeder
{
    public function run(): void
    {
        $president = OrgChartNode::create([
            'name'       => 'Dr. Juan dela Cruz',
            'title'      => 'College President',
            'department' => 'Office of the President',
            'sort_order' => 0,
        ]);

        $vp = OrgChartNode::create([
            'name'       => 'Maria Santos, Ph.D.',
            'title'      => 'Vice President for Academic Affairs',
            'department' => 'VPAA',
            'parent_id'  => $president->id,
            'sort_order' => 0,
        ]);

        OrgChartNode::create([
            'name'       => 'Jose Reyes',
            'title'      => 'Dean, College of Engineering',
            'department' => 'COE',
            'parent_id'  => $vp->id,
            'sort_order' => 0,
        ]);
    }
}
