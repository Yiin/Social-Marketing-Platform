<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGooglePlusQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_plus_queues', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('queue_id')->unsiged();
            $table->foreign('queue_id')->references('id')->on('queues')->onUpdate('cascade')->onDelete('cascade');
            $table->text('message');
            $table->string('url');
            $table->boolean('isImageUrl');

            // statistics
            $table->integer('communities_reached');
            $table->integer('impressions');

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
        Schema::dropIfExists('google_plus_queues');
    }
}
