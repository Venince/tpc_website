<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdmissionSection;
use App\Models\AdmissionItem;

class AdmissionSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Freshmen Requirements ──────────────────────────────────────
        $freshmen = AdmissionSection::create([
            'key'        => 'freshmen',
            'type'       => 'list',
            'label'      => 'For Freshmen',
            'note'       => null,
            'is_visible' => true,
            'order'      => 1,
        ]);

        foreach ([
            'Report Card (Form 138)',
            'Good Moral Certificate',
            'PSA Birth Certificate',
            '2x2 ID Pictures (recent)',
            'Certificate of Completion (if available)',
        ] as $i => $item) {
            AdmissionItem::create([
                'admission_section_id' => $freshmen->id,
                'title' => $item,
                'body'  => null,
                'order' => $i + 1,
            ]);
        }

        // ── 2. Transferee Requirements ────────────────────────────────────
        $transferee = AdmissionSection::create([
            'key'        => 'transferee',
            'type'       => 'list',
            'label'      => 'For Transferees',
            'note'       => null,
            'is_visible' => true,
            'order'      => 2,
        ]);

        foreach ([
            'Transcript of Records / Copy of Grades',
            'Honorable Dismissal',
            'Good Moral Certificate',
            'PSA Birth Certificate',
            '2x2 ID Pictures (recent)',
        ] as $i => $item) {
            AdmissionItem::create([
                'admission_section_id' => $transferee->id,
                'title' => $item,
                'body'  => null,
                'order' => $i + 1,
            ]);
        }

        // ── 3. Requirements Note ──────────────────────────────────────────
        AdmissionSection::create([
            'key'        => 'requirements_note',
            'type'       => 'note',
            'label'      => 'Note',
            'note'       => 'Requirements may vary depending on the program. Please confirm with the admissions office for the latest official list.',
            'is_visible' => true,
            'order'      => 3,
        ]);

        // ── 4. Enrollment Process ─────────────────────────────────────────
        $process = AdmissionSection::create([
            'key'        => 'process',
            'type'       => 'steps',
            'label'      => 'Enrollment Process',
            'note'       => null,
            'is_visible' => true,
            'order'      => 4,
        ]);

        foreach ([
            ['Prepare your requirements',        'Complete the documents listed above before visiting the office.'],
            ['Submit documents to Admissions',   'Proceed to the admissions office and present your documents for screening.'],
            ['Choose your program',              'Select the program that best fits your goals and career path.'],
            ['Assessment and payment',           'Follow the cashier and assessment instructions as directed.'],
            ['Confirm enrollment',               'Receive your confirmation slip and final enrollment instructions.'],
        ] as $i => [$title, $body]) {
            AdmissionItem::create([
                'admission_section_id' => $process->id,
                'title' => $title,
                'body'  => $body,
                'order' => $i + 1,
            ]);
        }

        // ── 5. Office Hours ───────────────────────────────────────────────
        $officeHours = AdmissionSection::create([
            'key'        => 'office_hours',
            'type'       => 'schedule',
            'label'      => 'Office Hours',
            'note'       => "If you're unsure about requirements, message the office first.",
            'is_visible' => true,
            'order'      => 5,
        ]);

        foreach ([
            ['Monday – Friday', '8:00 AM – 5:00 PM'],
            ['Saturday',        '8:00 AM – 12:00 PM'],
            ['Sunday',          'Closed'],
        ] as $i => [$day, $hours]) {
            AdmissionItem::create([
                'admission_section_id' => $officeHours->id,
                'title' => $day,
                'body'  => $hours,
                'order' => $i + 1,
            ]);
        }
    }
}
