<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashflows', function (Blueprint $table) {
            $table->id();
            $table->date('transdate');
            $table->string('note',100);
            $table->string('from_acc',30);
            $table->string('to_acc',30);
            $table->decimal('debit',15,2);
            $table->decimal('credit',15,2);
            $table->decimal('balance',15,2);
            $table->string('refdoc',50);
            $table->string('efile',250);
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
        Schema::dropIfExists('cashflows');
    }
}
