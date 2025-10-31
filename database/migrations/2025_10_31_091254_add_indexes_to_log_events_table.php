<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('log_events', function (Blueprint $table) {
            $table->index(
                ['market_id', 'event_name_id', 'created_at', 'session_id', 'deleted_at'],
                'idx_log_events_funnel_cover'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_events', function (Blueprint $table) {
            $table->dropIndex('idx_log_events_funnel_cover');
        });
    }
};
