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
            $table->unsignedBigInteger('author')->default(0);
            $table->unsignedBigInteger('updated_by')->default(0);
            $table->tinyInteger('status_id')->unsigned()->default(0);
            $table->softDeletes()->comment('記事の削除は論理削除とする');
            $table->timestamps();
            $table->foreign('author')->references('id')->on('users');
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
