<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();

            // Section type: text | list | gallery
            $table->string('type')->default('text');

            // Heading shown above the section
            $table->string('heading')->nullable();

            // Body — used by 'text' type (paragraph prose)
            $table->text('body')->nullable();

            // Items — used by 'list' type, stored as JSON array of strings
            $table->json('items')->nullable();

            // Image path — used by 'gallery' type (one row per image)
            $table->string('image_path')->nullable();

            // Caption for gallery images (no length limit — uses text)
            $table->text('caption')->nullable();

            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_details');
    }
};
