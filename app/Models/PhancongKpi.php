<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhancongKpi extends Model
{
    use HasFactory;

    protected $table = 'phancong_kpi';
    protected $primaryKey = 'ID_Phancong';

    // Tắt timestamps vì table không có created_at, updated_at
    public $timestamps = false;

    protected $fillable = [
        'ID_kpi',
        'ID_user',
        'ID_phongban',
        'ID_nguoi_phan_cong',
        'Ngay_batdau',
        'Ngay_ketthuc',
        'Trang_thai',
        'ID_loai_kpi'
    ];

    protected $casts = [
        'Ngay_batdau' => 'date',
        'Ngay_ketthuc' => 'date'
    ];

    public function kpi()
    {
        return $this->belongsTo(Kpi::class, 'ID_kpi', 'ID_kpi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_user', 'ID_user');
    }

    public function phongban()
    {
        return $this->belongsTo(Phongban::class, 'ID_phongban', 'ID_phongban');
    }

    public function nguoiPhanCong()
    {
        return $this->belongsTo(User::class, 'ID_nguoi_phan_cong', 'ID_user');
    }

    public function loaiKpi()
    {
        return $this->belongsTo(LoaiKpi::class, 'ID_loai_kpi', 'ID_loai_kpi');
    }
    public function danhgiaKpi()
    {
        return $this->hasOne(DanhgiaKpi::class, 'ID_phancong', 'ID_Phancong');
    }

    public function dulieuKpi()
    {
        return $this->hasMany(DulieuKpi::class, 'ID_phancong', 'ID_Phancong');
    }
}

