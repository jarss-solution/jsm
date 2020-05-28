<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesAssigneesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues_assignees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('issue_id')->unsigned()->nullable();
            $table->foreign('issue_id')->references('id')->on('issues')->onDelete('cascade');
            $table->bigInteger('assignee_id')->unsigned()->nullable();
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('issues_assignees');
    }
}
