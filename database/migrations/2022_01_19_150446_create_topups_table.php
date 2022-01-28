<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topups', function (Blueprint $table) {
            $table->id();
            $table->string('idplayer',50);
            $table->string('playername',100);
            $table->decimal('amount',15,2);
            $table->decimal('topup_bonus',15,2);
            $table->date('topupdate');
            $table->string('topup_status',50);
            $table->string('rekening_tujuan',30);
            $table->string('efile')->nullable();
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
        Schema::dropIfExists('topups');
    }
}
