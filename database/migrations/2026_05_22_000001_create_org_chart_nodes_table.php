<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_chart_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('department')->nullable();
            $table->string('photo')->nullable();       // stored in storage/public/org-chart/
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('org_chart_nodes')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_chart_nodes');
    }
};
