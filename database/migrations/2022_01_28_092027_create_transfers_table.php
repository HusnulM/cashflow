<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_transfer');
            $table->string('rekening_asal',100);
            $table->string('rekening_tujuan',100);
            $table->decimal('jml_transfer',15,2);
            $table->decimal('biaya_transfer',15,2);
            $table->string('keterangan',100);
            $table->string('createdby',50);
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
        Schema::dropIfExists('transfers');
    }
}
