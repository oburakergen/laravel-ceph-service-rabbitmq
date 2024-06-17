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
        Schema::create('user_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->unsignedBigInteger('max_file_size')->default(1024 * 100);
            $table->unsignedBigInteger('max_file_count')->default(5);
            $table->unsignedBigInteger('max_storage')->default(1024 * 1024 * 5);
            $table->unsignedFloat('used_storage',20, 2)->default(0.0);
            $table->timestamp('expires_at')->default(now()->addMonth());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_licenses');
    }
};
