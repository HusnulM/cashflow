<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('bankcode',30);
            $table->string('bankacc',30);
            $table->decimal('totalcoin',15,2);
            $table->timestamps();

            // $table->primary(['object']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_stocks');
    }
}
