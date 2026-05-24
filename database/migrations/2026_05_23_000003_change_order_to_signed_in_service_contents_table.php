<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_contents', function (Blueprint $table) {
            $table->smallInteger('order')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('service_contents', function (Blueprint $table) {
            $table->unsignedSmallInteger('order')->default(0)->change();
        });
    }
};
