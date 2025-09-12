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
        Schema::create('super_admin_menus', function (Blueprint $table) {
            $table->id();
            $table->string('modulename', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('route', 255)->nullable();
            $table->string('menuname', 255)->nullable();
            $table->string('submenuname', 255)->nullable();
            $table->integer('module_id')->default(0);
            $table->integer('menu_id')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('super_admin_menus');
    }
};
