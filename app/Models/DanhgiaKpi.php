<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhgiaKpi extends Model
{
    use HasFactory;

    protected $table = 'danhgia_kpi';
    protected $primaryKey = 'ID_danhgia';

    public $timestamps = false;

    protected $fillable = [
        'ID_phancong',
        'Ty_le_hoanthanh',
        'Ketqua_thuchien',
        'Trang_thai',
        'ID_nguoithamdinh',
        'Ngay_thamdinh',
        'Nhan_xet'
    ];

    protected $casts = [
        'Ty_le_hoanthanh' => 'decimal:2',
        'Ngay_thamdinh' => 'datetime'
    ];

    public function phancongKpi()
    {
        return $this->belongsTo(PhancongKpi::class, 'ID_phancong', 'ID_Phancong');
    }

    public function nguoiThamDinh()
    {
        return $this->belongsTo(User::class, 'ID_nguoithamdinh', 'ID_user');
    }
}
