<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DulieuKpi extends Model
{
    use HasFactory;

    protected $table = 'dulieu_kpi';
    protected $primaryKey = 'ID_dulieu';

    public $timestamps = false;

    protected $fillable = [
        'ID_phancong',
        'ID_nguoigui',
        'Minh_chung',
        'File_path',
        'File_name'
    ];

    protected $casts = [
        'Ngay_gui' => 'datetime',
        'Ketqua_thuchien' => 'decimal:0'
    ];

    public function phancongKpi()
    {
        return $this->belongsTo(PhancongKpi::class, 'ID_phancong', 'ID_Phancong');
    }

    public function nguoigui()
    {
        return $this->belongsTo(User::class, 'ID_nguoigui', 'ID_user');
    }
}
