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
        Schema::table('load_registers', function (Blueprint $table) {
            $table->foreign('id_load')->references('id_load')->on('loads')->onUpdate('cascade');
            ;
            $table->foreign('id_register_type')->references('id_register_type')->on('register_type')->onUpdate('cascade');
            ;
            $table->foreign('id_truck')->references('id_truck')->on('trucks')->onUpdate('cascade');
            ;
            $table->foreign('id_user')->references('id_user')->on('users')->onUpdate('cascade');
            ;
        });
        Schema::table('login_register', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
