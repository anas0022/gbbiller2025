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
        Schema::create('super_admin_submenus', function (Blueprint $table) {
            $table->id();
                        $table->string('menuname', 255)->unique();
            $table->integer('menu_module');
            $table->integer('menu_id');
            $table->string('sub_route', 255)->unique();
           $table->integer('Status')->nullable()->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('super_admin_submenus');
    }
};
