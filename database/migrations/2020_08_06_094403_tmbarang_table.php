<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TmbarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tmbarang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('foto', 50);
            $table->string('barangnm', 50);
            $table->string('hargabeli', 50);
            $table->string('hargajual', 50);
            $table->string('stok', 50);
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
        //
    }
}
