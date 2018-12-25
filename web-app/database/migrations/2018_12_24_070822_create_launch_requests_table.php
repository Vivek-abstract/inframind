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
            $table->string('database_ip', 20)->nullable();
            $table->string('ws1_ip', 20)->nullable();
            $table->string('ws2_ip', 20)->nullable();
            $table->string('dns_name', 150)->nullable();
            $table->text('output')->nullable();
            $table->string('status', 10)->nullable();
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
