<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('featured_image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('social_links')->nullable();
            $table->timestamps();
        });

        Schema::create('service_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['text', 'image'])->default('text');
            $table->text('body')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_caption')->nullable();
            $table->string('heading')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_contents');
        Schema::dropIfExists('services');
    }
};
