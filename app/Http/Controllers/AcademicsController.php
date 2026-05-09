<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramPerson;

class AcademicsController extends Controller
{
    public function index()
    {
        return view('public.academics', [
            'programs' => Program::where('is_active', true)->orderBy('code')->get(),
        ]);
    }

    public function show(Program $program)
    {
        abort_if(! $program->is_active, 404);

        $program->load(['people', 'achievements']);

        $head         = $program->people->where('role', ProgramPerson::ROLE_HEAD)->values();
        $coordinators = $program->people->where('role', ProgramPerson::ROLE_COORDINATOR)->values();
        $instructors  = $program->people->where('role', ProgramPerson::ROLE_INSTRUCTOR)->values();
        $achievements = $program->achievements;

        // Sidebar: other active programs excluding current
        $otherPrograms = Program::where('is_active', true)
            ->where('id', '!=', $program->id)
            ->orderBy('code')
            ->get();

        return view('public.program', compact(
            'program', 'head', 'coordinators', 'instructors', 'achievements', 'otherPrograms'
        ));
    }
}
