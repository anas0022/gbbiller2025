<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscription_modules', function (Blueprint $table) {
            $table->string('Sub_type', 255)->unique();
            $table->string('icon', 255);
        });
    }

    public function down()
    {
        Schema::table('subscription_modules', function (Blueprint $table) {
            $table->dropColumn('Sub_type');
            $table->dropColumn('icon');
        });
    }
};