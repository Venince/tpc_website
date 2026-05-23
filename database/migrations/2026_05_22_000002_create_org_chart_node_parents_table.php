<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_chart_node_parents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('node_id');
            $table->unsignedBigInteger('parent_id');
            $table->timestamps();

            $table->unique(['node_id', 'parent_id']);

            $table->foreign('node_id')
                  ->references('id')->on('org_chart_nodes')
                  ->cascadeOnDelete();

            $table->foreign('parent_id')
                  ->references('id')->on('org_chart_nodes')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_chart_node_parents');
    }
};
