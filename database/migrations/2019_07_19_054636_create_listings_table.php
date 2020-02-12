<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->bigIncrements('id'); //identify listings by id
            $table->string('title');
            $table->text('body');
            $table->integer('user_id')->unsigned(); //creation of user id
            $table->integer('area_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->boolean('live')->default(false); //listig is only live when we pay for it
            $table->softDeletes(); //column for the deleted listings in case someone deletes it
            $table->timestamps();

            #$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); //deletes user listings if user is deleted
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }
}
