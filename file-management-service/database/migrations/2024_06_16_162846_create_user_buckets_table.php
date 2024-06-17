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
        Schema::create('user_buckets', function (Blueprint $table) {
            $table->id();
            $table->string('bucket_name')->nullable();
            $table->string('bucket_region')->nullable();
            $table->string('bucket_key')->nullable();
            $table->string('bucket_secret')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_license_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_buckets');
    }
};
