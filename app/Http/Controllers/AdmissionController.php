<?php

namespace App\Http\Controllers;

use App\Models\AdmissionSection;

class AdmissionController extends Controller
{
    public function index()
    {
        // Load all visible sections with their items, ordered correctly
        $sections = AdmissionSection::with('items')
            ->where('is_visible', true)
            ->orderBy('order')
            ->get()
            ->keyBy('key');   // Access by key: $sections['freshmen'], $sections['process'], etc.

        return view('public.admission', compact('sections'));
    }
}
