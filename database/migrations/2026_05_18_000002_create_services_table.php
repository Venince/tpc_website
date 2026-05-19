<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();

            // Content type: 'text' or 'image'
            $table->enum('type', ['text', 'image'])->default('text');

            // For text blocks
            $table->text('body')->nullable();

            // For image blocks
            $table->string('image_path')->nullable();
            $table->string('image_caption')->nullable();

            // Optional heading for any section
            $table->string('heading')->nullable();

            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_contents');
    }
};
