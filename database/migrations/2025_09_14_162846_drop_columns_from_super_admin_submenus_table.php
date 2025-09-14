<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('super_admin_submenus', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down()
    {
        Schema::table('super_admin_submenus', function (Blueprint $table) {
            // Note: Column restoration requires manual implementation
            // Original columns dropped: status
        });
    }
};