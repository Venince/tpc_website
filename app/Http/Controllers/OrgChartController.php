<?php

namespace App\Http\Controllers;

use App\Models\OrgChartNode;

class OrgChartController extends Controller
{
    public function index()
    {
        $tree = OrgChartNode::loadTree();

        return view('public.org-chart.show', compact('tree'));
    }
}
