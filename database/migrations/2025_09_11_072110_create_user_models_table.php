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
        Schema::create('user_models', function (Blueprint $table) {
             $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile', 20)->nullable();
            $table->string('password');
            $table->string('role')->nullable();
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('mobile_code', 10)->nullable();
            $table->string('plan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('biller_code')->nullable(); // ✅ extra field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_models');
    }
};
