<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_queues', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('queue_id')->unsiged();
            $table->foreign('queue_id')->references('id')->on('queues')->onUpdate('cascade')->onDelete('cascade');
            $table->string('caption');
            $table->string('name');
            $table->text('message');
            $table->string('url');
            $table->string('image_url');
            $table->text('description');

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
        Schema::dropIfExists('facebook_queues');
    }
}
