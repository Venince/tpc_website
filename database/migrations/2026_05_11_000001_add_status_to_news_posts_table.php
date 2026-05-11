<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('news_posts', function (Blueprint $table) {
            // 'pending'  = waiting for superadmin review
            // 'approved' = superadmin approved
            // 'declined' = superadmin declined
            $table->string('status')->default('pending')->after('is_published');
            $table->text('review_note')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('review_note');
            $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')
                  ->constrained('users')->nullOnDelete();
        });

        // Migrate existing data: any already-published post -> approved
        DB::table('news_posts')
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->update(['status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('news_posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn(['status', 'review_note', 'reviewed_at']);
        });
    }
};
