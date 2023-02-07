<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('no title');
            $table->mediumText('content');
            $table->unsignedBigInteger('author')->unsigned()->default(1);
            $table->unsignedBigInteger('updated_by')->unsigned()->nullable();
            $table->unsignedSmallInteger('status_id')->unsigned()->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('author')->references('id')->on('users');
            // $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
