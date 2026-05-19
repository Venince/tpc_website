<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function show(Service $service)
    {
        // 404 if inactive
        abort_unless($service->is_active, 404);

        $service->load('contents');

        return view('public.services.show', compact('service'));
    }
}
