<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiKpi extends Model
{
    use HasFactory;

    protected $table = 'loai_kpi';
    protected $primaryKey = 'ID_loai_kpi';

    // Tắt timestamps vì table không có created_at, updated_at
    public $timestamps = false;

    protected $fillable = [
        'Ten_loai_kpi'
    ];

    public function kpis()
    {
        return $this->hasMany(Kpi::class, 'ID_loai_kpi', 'ID_loai_kpi');
    }
}
