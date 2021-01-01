<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticleCommentTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_comment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('article_id')->comment('文章ID');
            $table->integer('comment_id')->comment('回复ID');
            $table->integer('create_user')->comment('评论人');
            $table->text('content')->comment('content');
            $table->integer('reply_times')->comment('回复量');
            $table->integer('like_times')->comment('点赞量');
            $table->integer('created_at')->comment('创建时间');
            $table->integer('updated_at')->comment('更新时间');
        });
        \Hyperf\DbConnection\Db::statement("alter table `article_comment` comment'情报中心文章评论'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_comment');
    }
}
