<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoUmkmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_umkms', function (Blueprint $table) {
            $table->id();
            $table->text('photo')->nullable();
            $table->text('url')->nullable();
            $table->unsignedBigInteger('umkm_id');
            $table->foreign('umkm_id')->references('id')->on('umkms')->onDelete('cascade');
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
        Schema::dropIfExists('photo_umkms');
    }
}
