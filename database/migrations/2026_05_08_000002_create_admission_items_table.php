<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_section_id')->constrained()->cascadeOnDelete();
            $table->string('title');                  // Main text / step title / day label
            $table->text('body')->nullable();         // Sub-text (used by steps & schedule rows)
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_items');
    }
};
