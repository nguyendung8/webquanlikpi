<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dulieu_task', function (Blueprint $table) {
            $table->id('ID_dulieu');
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->text('Minh_chung');
            $table->string('File_name')->nullable();
            $table->string('File_path')->nullable();
            $table->timestamp('Ngay_gui')->useCurrent();
            $table->timestamps();

            $table->foreign('task_id')->references('ID_task')->on('tasks')->onDelete('cascade');
            $table->foreign('user_id')->references('ID_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dulieu_task');
    }
};

