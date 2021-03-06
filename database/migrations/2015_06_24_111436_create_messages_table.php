<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('from_id')->nullable();
            $table->integer('to_id');
            $table->integer('thread_id')->nullable();
            $table->integer('comment_id')->nullable();
            $table->integer('tag_id')->nullable();
            $table->tinyInteger('type');
            $table->mediumText('message')->nullable();
            $table->tinyInteger('read')->default(0);
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
        Schema::drop('messages');
    }
}
