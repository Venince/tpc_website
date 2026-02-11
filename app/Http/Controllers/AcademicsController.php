<?php

namespace App\Http\Controllers;

use App\Models\Program;

class AcademicsController extends Controller
{
    public function index()
    {
        return view('public.academics', [
            'programs' => Program::where('is_active', 1)->orderBy('code')->get(),
        ]);
    }
}
