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
        Schema::create('log_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained('markets')->onDelete('cascade');
            $table->foreignId('event_name_id')->constrained('event_names')->onDelete('cascade');
            $table->string('session_id', 255);
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['market_id', 'session_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_events');
    }
};
