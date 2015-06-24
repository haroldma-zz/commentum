<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id');
            $table->float('momentum')->default(0);
            $table->string('title', 35);
            $table->string('display_title', 35);
            $table->mediumText('description')->nullable();
            $table->string('hero_img')->nullable();
            $table->tinyInteger('privacy');
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
        Schema::drop('tags');
    }
}
