<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;

    protected $table = 'kpi';
    protected $primaryKey = 'ID_kpi';

    public $timestamps = false;

    protected $fillable = [
        'Ten_kpi',
        'Chi_tieu',
        'Donvi_tinh',
        'Do_uu_tien',
        'Ngay_tao',
        'Mo_ta',
        'ID_loai_kpi'
    ];

    protected $casts = [
        'Ngay_tao' => 'datetime',
        'Chi_tieu' => 'decimal:0'
    ];

    public function loaiKpi()
    {
        return $this->belongsTo(LoaiKpi::class, 'ID_loai_kpi', 'ID_loai_kpi');
    }

    public function phancongKpi()
    {
        return $this->hasMany(PhancongKpi::class, 'ID_kpi', 'ID_kpi');
    }
}
