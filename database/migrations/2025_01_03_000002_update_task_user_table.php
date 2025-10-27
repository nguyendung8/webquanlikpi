<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Chỉ thêm cột comment vào task_user, các cột khác đã có sẵn
        // (minh_chung, file_name, file_path, Ngay_gui sẽ ở bảng dulieu_task)
        Schema::table('task_user', function (Blueprint $table) {
            // Kiểm tra xem cột đã tồn tại chưa
            if (!Schema::hasColumn('task_user', 'comment')) {
                $table->text('comment')->nullable()->after('trang_thai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('task_user', function (Blueprint $table) {
            if (Schema::hasColumn('task_user', 'comment')) {
                $table->dropColumn('comment');
            }
        });
    }
};

