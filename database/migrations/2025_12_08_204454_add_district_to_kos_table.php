<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kos', function (Blueprint $table) {
            if (!Schema::hasColumn('kos','district')) {
                $table->string('district')->nullable()->after('city');
            }
        });
    }
    public function down()
    {
        Schema::table('kos', function (Blueprint $table) {
            if (Schema::hasColumn('kos','district')) {
                $table->dropColumn('district');
            }
        });
    }
};
