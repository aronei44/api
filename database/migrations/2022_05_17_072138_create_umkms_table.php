<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmkmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('provinsi');
            $table->integer('provinsi_id');
            $table->string('kabupaten');
            $table->integer('kabupaten_id');
            $table->string('kecamatan');
            $table->integer('kecamatan_id')->nullable();
            $table->string('desa');
            $table->integer('desa_id')->nullable();
            $table->text('alamat');
            $table->text('deskripsi');
            $table->text('bidang_usaha');
            $table->string('no_hp');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('umkms');
    }
}
