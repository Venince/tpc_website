<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();      // site_name, address, email, phone, etc.
            $table->text('value')->nullable();    // store as string (or json)
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('site_settings');
    }
};
