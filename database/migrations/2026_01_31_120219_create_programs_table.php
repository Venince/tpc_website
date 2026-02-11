<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();     // BSIS, BSAIS, etc.
            $table->string('name');                  // Bachelor of Science in ...
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable(); // store path, optional
            $table->string('department')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('programs');
    }
};
