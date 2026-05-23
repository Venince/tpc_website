<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrgChartNode extends Model
{
    protected $fillable = [
        'name', 'title', 'department', 'photo',
        'parent_id', 'sort_order', 'row', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
        'row'        => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    /** Legacy single parent (kept for backward compat) */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrgChartNode::class, 'parent_id');
    }

    /** Legacy single-parent children */
    public function children(): HasMany
    {
        return $this->hasMany(OrgChartNode::class, 'parent_id')
                    ->orderBy('sort_order');
    }

    /** Multiple parents via pivot */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            OrgChartNode::class,
            'org_chart_node_parents',
            'node_id',
            'parent_id'
        );
    }

    /** Multiple children via pivot */
    public function multiChildren(): BelongsToMany
    {
        return $this->belongsToMany(
            OrgChartNode::class,
            'org_chart_node_parents',
            'parent_id',
            'node_id'
        )->orderBy('sort_order');
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        // Root = no entries in the pivot table as a child
        return $query->whereNotIn('id', function ($sub) {
            $sub->select('node_id')->from('org_chart_node_parents');
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function allChildren(): BelongsToMany
    {
        return $this->belongsToMany(
            OrgChartNode::class,
            'org_chart_node_parents',
            'parent_id',
            'node_id'
        )
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->with('allChildren');   // recursive eager-load
    }

    public static function loadTree(): \Illuminate\Support\Collection
    {
        return static::with('allChildren')
                    ->active()
                    ->roots()
                    ->ordered()
                    ->get();
    }

    public function photoUrl(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }

        $initials = collect(explode(' ', $this->name))
            ->map(fn ($w) => strtoupper($w[0] ?? ''))
            ->take(2)
            ->implode('');

        return 'https://ui-avatars.com/api/?name=' . urlencode($initials)
             . '&background=1a5632&color=fff&size=128&bold=true';
    }

    // ── Sync parents helper ───────────────────────────────────────────────

    public function syncParents(array $parentIds): void
    {
        // Remove self from parent IDs to prevent cycles
        $parentIds = array_filter($parentIds, fn ($id) => $id != $this->id);
        $this->parents()->sync($parentIds);

        // Keep legacy parent_id as the first selected parent for compatibility
        $this->update(['parent_id' => count($parentIds) ? $parentIds[0] : null]);
    }
}
