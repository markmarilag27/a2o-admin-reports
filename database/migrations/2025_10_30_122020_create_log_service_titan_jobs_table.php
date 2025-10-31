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
        Schema::create('log_service_titan_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained('markets')->onDelete('cascade');
            $table->unsignedBigInteger('service_titan_job_id')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->string('job_status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['market_id', 'start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_service_titan_jobs');
    }
};
