<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_achievement_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_achievement_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_achievement_images');
    }
};
