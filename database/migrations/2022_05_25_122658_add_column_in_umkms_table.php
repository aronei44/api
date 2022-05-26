<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInUmkmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('umkms', function (Blueprint $table) {
            $table->string('owner_name')->nullable();
            $table->enum('gender',['l','p'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('umkms', function (Blueprint $table) {
            $table->dropColumn('owner_name');
            $table->dropColumn('gender');
        });
    }
}
