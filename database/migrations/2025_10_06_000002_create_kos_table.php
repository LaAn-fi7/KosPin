<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->decimal('price_per_month', 10, 2);
            $table->json('facilities')->nullable();
            $table->json('images')->nullable();
            $table->enum('gender', ['male', 'female', 'mixed']);
            $table->boolean('is_available')->default(true);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kos');
    }
};