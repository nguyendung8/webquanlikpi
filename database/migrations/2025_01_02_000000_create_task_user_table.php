<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('trang_thai', ['chua_bat_dau', 'dang_thuc_hien', 'hoan_thanh'])->default('chua_bat_dau');
            $table->timestamps();

            $table->foreign('task_id')->references('ID_task')->on('tasks')->onDelete('cascade');
            $table->foreign('user_id')->references('ID_user')->on('users')->onDelete('cascade');

            $table->unique(['task_id', 'user_id']);
        });

        // Migrate dữ liệu cũ từ tasks.ID_user_duocgiao sang task_user
        DB::statement('
            INSERT INTO task_user (task_id, user_id, trang_thai, created_at, updated_at)
            SELECT ID_task, ID_user_duocgiao, Trang_thai, NOW(), NOW()
            FROM tasks
            WHERE ID_user_duocgiao IS NOT NULL
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('task_user');
    }
};

