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
        Schema::create('load_registers', function (Blueprint $table) {
            $table->id('id_load_register');
            $table->unsignedBigInteger('id_load');
            $table->unsignedBigInteger('id_register_type');
            $table->unsignedBigInteger('id_truck');
            $table->unsignedBigInteger('id_user');
            $table->unsignedInteger('status')->default(1);
            $table->date('register_date');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('load_registers');
    }
};
