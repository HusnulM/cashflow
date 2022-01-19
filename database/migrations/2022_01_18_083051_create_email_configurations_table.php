<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('driver', 20);
            $table->string('host', 50);
            $table->string('port', 50);
            $table->string('user_name', 50);
            $table->string('encryption', 50);
            $table->string('password', 50);
            $table->string('sender_name', 50);
            $table->string('sender_email', 50);
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
        Schema::dropIfExists('email_configurations');
    }
}
