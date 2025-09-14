<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('super_admin_submenus', function (Blueprint $table) {
            $table->string('sub_route', 255)->unique();
            $table->integer('menu_module');
        });
    }

    public function down()
    {
        Schema::table('super_admin_submenus', function (Blueprint $table) {
            $table->dropColumn('sub_route');
            $table->dropColumn('menu_module');
        });
    }
};