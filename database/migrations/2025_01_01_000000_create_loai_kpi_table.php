<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loai_kpi', function (Blueprint $table) {
            $table->id('ID_loai_kpi');
            $table->string('Ten_loai_kpi', 100);
            $table->timestamps();
        });

        // Thêm cột ID_loai_kpi vào bảng kpi
        Schema::table('kpi', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_loai_kpi')->nullable()->after('Mo_ta');
            $table->foreign('ID_loai_kpi')->references('ID_loai_kpi')->on('loai_kpi');
        });

        // Thêm cột ID_loai_kpi vào bảng phancong_kpi
        Schema::table('phancong_kpi', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_loai_kpi')->nullable()->after('Trang_thai');
            $table->foreign('ID_loai_kpi')->references('ID_loai_kpi')->on('loai_kpi');
        });
    }

    public function down()
    {
        Schema::table('phancong_kpi', function (Blueprint $table) {
            $table->dropForeign(['ID_loai_kpi']);
            $table->dropColumn('ID_loai_kpi');
        });

        Schema::table('kpi', function (Blueprint $table) {
            $table->dropForeign(['ID_loai_kpi']);
            $table->dropColumn('ID_loai_kpi');
        });

        Schema::dropIfExists('loai_kpi');
    }
};
