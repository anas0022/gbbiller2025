<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscription_modules', function (Blueprint $table) {
            $table->integer('status')->default('1');
        });
    }

    public function down()
    {
        Schema::table('subscription_modules', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};