<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('description');
            $table->string('location');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->enum('status', ['OPEN', 'ASSIGNED', 'COMPLETED']);
            $table->date('due_date');
            $table->string('due_time');
            $table->unsignedSmallInteger('budget');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}