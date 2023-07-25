<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('username');
            $table->string('email');
            $table->string('password');

            $table->string('alamat')->nullable();
            $table->string('tempatLahir')->nullable();
            $table->string('tanggalLahir')->nullable();

            $table->string('jenisKelamin')->nullable();
            $table->string('profilePhoto')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
