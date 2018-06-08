<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bxwx_id', 50)->comment('笔下文学id');
            $table->string('title', 100)->comment('小说标题');
            $table->string('author', 50)->comment('作者');
            $table->smallInteger('type')->comment('小说类型');
            $table->string('image', 150)->comment('封面');
            $table->text('introduction')->comment('简介');
            $table->integer('read_count')->comment('阅读数');
            $table->smallInteger('is_ending')->comment('完本1=否 2=是');
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
        Schema::dropIfExists('books');
    }
}
