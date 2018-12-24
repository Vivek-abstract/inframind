<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaunchRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('launch_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('company_name', 50);
            $table->string('requester', 50);
            $table->string('contact', 20);
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
        Schema::dropIfExists('launch_requests');
    }
}
