<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatisticFieldsToQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('queues', function (Blueprint $table) {
            // G+, FB, LI: groups and communities we posted to
            // Twitter: tweets posted
            $table->integer('statistic_groups')->default(0);
            // G+, FB, LI: sum of members in groups
            // Twitter: sum of followers
            $table->integer('statistic_members')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn('statistic_groups');
            $table->dropColumn('statistic_members');
        });
    }
}
