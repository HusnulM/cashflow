<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcountingDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acounting_docs', function (Blueprint $table) {
            $table->string('docnum',20);
            $table->integer('docyear');
            $table->date('trans_date');
            $table->string('trans_type',20);
            $table->decimal('total_amount',15,2);
            $table->string('account',50);
            $table->string('bank_account',50);
            $table->string('idplayer',70);
            $table->string('createdby',50);
            $table->timestamps();

            $table->primary(['docnum','docyear']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acounting_docs');
    }
}
