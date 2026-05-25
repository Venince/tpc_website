<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrgChartNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrgChartController extends Controller
{
    public function index()
    {
        $nodes = OrgChartNode::with('parents')
                             ->orderBy('sort_order')
                             ->get();

        return view('admin.org-chart.index', compact('nodes'));
    }

    public function create()
    {
        $parents = OrgChartNode::active()->ordered()->get();
        $node    = null;

        return view('admin.org-chart.create', compact('parents', 'node'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:120',
            'title'       => 'required|string|max:120',
            'department'  => 'nullable|string|max:120',
            'parent_ids'  => 'nullable|array',
            'parent_ids.*'=> 'exists:org_chart_nodes,id',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
            'photo'       => 'nullable|image|max:2048',
            'row'         => 'nullable|integer|min:1|max:9',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                                     ->store('org-chart', 'public');
        }

        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = OrgChartNode::max('sort_order') + 1;
        $data['row'] = $request->input('row', 1);
        $data['parent_id']  = isset($data['parent_ids'][0]) ? $data['parent_ids'][0] : null;

        $node = OrgChartNode::create($data);
        $node->syncParents($request->input('parent_ids', []));

        return redirect()->route('admin.org-chart.index')
                         ->with('success', 'Person added to the org chart.');
    }

    public function edit(OrgChartNode $orgChart)
    {
        $parents = OrgChartNode::where('id', '!=', $orgChart->id)
                               ->ordered()->get();

        $orgChart->load('parents');

        return view('admin.org-chart.edit', compact('orgChart', 'parents'));
    }

    public function update(Request $request, OrgChartNode $orgChart)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:120',
            'title'       => 'required|string|max:120',
            'department'  => 'nullable|string|max:120',
            'parent_ids'  => 'nullable|array',
            'parent_ids.*'=> 'exists:org_chart_nodes,id',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
            'photo'       => 'nullable|image|max:2048',
            'row'         => 'nullable|integer|min:1|max:9',
        ]);

        if ($request->hasFile('photo')) {
            if ($orgChart->photo) {
                Storage::disk('public')->delete($orgChart->photo);
            }
            $data['photo'] = $request->file('photo')
                                     ->store('org-chart', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['row'] = $request->input('row', 1);
        $data['parent_id'] = isset($request->parent_ids[0]) ? $request->parent_ids[0] : null;

        $orgChart->update($data);
        $orgChart->syncParents($request->input('parent_ids', []));

        return redirect()->route('admin.org-chart.index')
                         ->with('success', 'Person updated.');
    }

    public function destroy(OrgChartNode $orgChart)
    {
        // Pivot entries are cascade-deleted automatically
        if ($orgChart->photo) {
            Storage::disk('public')->delete($orgChart->photo);
        }

        $orgChart->delete();

        return redirect()->route('admin.org-chart.index')
                         ->with('success', 'Person removed from the org chart.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items'          => 'required|array',
            'items.*.id'     => 'required|exists:org_chart_nodes,id',
            'items.*.order'  => 'required|integer',
            'items.*.parent' => 'nullable|exists:org_chart_nodes,id',
        ]);

        foreach ($request->items as $item) {
            $node = OrgChartNode::find($item['id']);
            $node->update(['sort_order' => $item['order']]);
            $parentId = $item['parent'] ?? null;
            $node->syncParents($parentId ? [$parentId] : []);
        }

        return response()->json(['ok' => true]);
    }
}
