<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('edition_id')->unsigned();
            $table->string('SID', 20);
            $table->string('SMS_phone')->default('');
            $table->string('SMS_token')->default('');
            $table->integer('SMS_attempts')->default(0)->unsigned();
            $table->boolean('SMS_verified')->default(0)->unsigned();
            $table->dateTime('SMS_time')->nullable();
            $table->boolean('ballot_cast')->default(0);
            $table->dateTime('ballot_time')->nullable();
            $table->string('signature', 64)->default('');
            $table->integer('by_user_id')->nullable()->unsigned();
            $table->ipAddress('ip_address')->default('');
            $table->string('user_agent')->default('');
            $table->timestamps();

            $table->foreign('edition_id')->references('id')->on('editions');
            $table->foreign('by_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voters');
    }
}
