<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->bigInteger('project_status_id')->unsigned()->nullable();
            $table->foreign('project_status_id')->references('id')->on('project_statuses')->onDelete('cascade');
            $table->text('title');
            $table->longText('description')->nullable();
            $table->enum('category', ['now', 'need', 'free', 'rockstar'])->default('need');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('estimated_time_hours')->nullable();
            $table->double('time_log')->default(0)->nullable();
            $table->integer('work_done_percent')->nullable();
            $table->enum('type', ['company', 'personal'])->default('company');
            $table->tinyInteger('status')->default(0);
            $table->integer('position')->default(0)->nullable();
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
        Schema::dropIfExists('issues');
    }
}
