<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->unsignedInteger('total_rooms')->default(0)->after('price_per_month');
        });
    }

    public function down()
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->dropColumn('total_rooms');
        });
    }
};
