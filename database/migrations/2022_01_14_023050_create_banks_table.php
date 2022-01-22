<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->string('bankid',10);
            $table->string('bankname',100);
            $table->string('bank_accountnumber',100);
            $table->string('bank_accountname',100);
            $table->string('bank_type',100);
            $table->decimal('opening_balance',15,2);
            $table->timestamps();

            $table->primary(['bankid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
