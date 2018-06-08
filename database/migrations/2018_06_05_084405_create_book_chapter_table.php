<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookChapterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_chapter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id')->comment('小说id');
            $table->integer('bxwx_id')->comment('笔下文学章节id');
            $table->string('title', 50)->comment('章节名');
            $table->text('content')->comment('内容');
            $table->integer('sort')->comment('排序');
            $table->softDeletes();
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
        Schema::dropIfExists('book_chapter');
    }
}
