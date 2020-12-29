<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateRoleRuleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_rule', function (Blueprint $table) {
            $table->integer('role_id')->comment('角色ID');
            $table->integer('rule_id')->comment('权限ID');
            $table->integer('created_at')->default('')->comment('创建时间');
            $table->integer('updated_at')->default('')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_rule');
    }
}
