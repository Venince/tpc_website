<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();          // e.g. freshmen, transferee, process, office_hours
            $table->string('type');                   // list | steps | schedule | note
            $table->string('label');                  // Display heading e.g. "For Freshmen"
            $table->text('note')->nullable();         // Optional callout/tip text below the section
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_sections');
    }
};
