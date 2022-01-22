<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNrivsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nrivs', function (Blueprint $table) {
            $table->string('object',20);
            $table->string('fromnumber',20);
            $table->string('tonumber',20);
            $table->string('currentnum',20);
            $table->timestamps();

            $table->primary(['object']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nrivs');
    }
}
