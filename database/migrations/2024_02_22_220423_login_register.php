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
        Schema::create('login_register', function (Blueprint $table) {
            $table->id('id_login');
            $table->unsignedBigInteger('id_user');
            $table->text('key');
            $table->string('ip');
            $table->string('city');
            $table->string('country');
            $table->unsignedInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_register');

    }
};
