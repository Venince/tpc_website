<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('org_chart_nodes', function (Blueprint $table) {
            $table->unsignedTinyInteger('row')->default(1)->after('sort_order');
            // 1 = same row as siblings, 2 = next row below
        });
    }
    public function down(): void {
        Schema::table('org_chart_nodes', function (Blueprint $table) {
            $table->dropColumn('row');
        });
    }
};
