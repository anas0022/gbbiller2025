<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('super_admin_menus', function (Blueprint $table) {
            $table->dropColumn('route');
        });
    }

    public function down()
    {
        Schema::table('super_admin_menus', function (Blueprint $table) {
            // Note: Column restoration requires manual implementation
            // Original columns dropped: route
        });
    }
};