<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateRuleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',64)->comment('权限菜单名称');
            $table->string('href',64)->comment('链接url');
            $table->string('rule',64)->comment('控制器方法');
            $table->integer('pid')->default(0)->comment('父级id');
            $table->tinyInteger('check')->default(1)->comment('是否需要验证');
            $table->tinyInteger('status')->default(0)->comment('是否显示');
            $table->integer('level')->comment('级别');
            $table->string('icon')->default('')->comment('图标');
            $table->smallInteger('sort')->default(0)->comment('排序');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rule');
    }
}
