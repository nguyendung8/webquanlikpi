<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nhatky extends Model
{
    use HasFactory;

    protected $table = 'nhatky';
    protected $primaryKey = 'ID_nhatky';

    public $timestamps = false;

    protected $fillable = [
        'ID_nguoithuchien',
        'Doi_tuong',
        'ID_doi_tuong',
        'Hanh_dong',
        'Gia_tri_cu',
        'Gia_tri_moi',
        'Ngay_thuchien'
    ];

    protected $casts = [
        'Ngay_thuchien' => 'datetime',
        'Gia_tri_cu' => 'array',
        'Gia_tri_moi' => 'array'
    ];

    public function nguoiThucHien()
    {
        return $this->belongsTo(User::class, 'ID_nguoithuchien', 'ID_user');
    }

    public function getDoiTuongNameAttribute()
    {
        switch ($this->Doi_tuong) {
            case 'user':
                $user = User::find($this->ID_doi_tuong);
                return $user ? $user->Ho_ten : 'Người dùng không tồn tại';
            case 'kpi':
                $kpi = Kpi::find($this->ID_doi_tuong);
                return $kpi ? $kpi->Ten_kpi : 'KPI không tồn tại';
            case 'phancong':
                $phancong = PhancongKpi::find($this->ID_doi_tuong);
                if ($phancong && $phancong->kpi) {
                    return $phancong->kpi->Ten_kpi;
                }
                return 'Phân công không tồn tại';
            default:
                return 'Đối tượng không xác định';
        }
    }

    public function getHanhDongTextAttribute()
    {
        $actions = [
            'them' => 'Thêm',
            'sua' => 'Sửa',
            'xoa' => 'Xóa',
            'duyet' => 'Duyệt'
        ];
        return $actions[$this->Hanh_dong] ?? $this->Hanh_dong;
    }

    public function getDoiTuongTextAttribute()
    {
        $objects = [
            'user' => 'Người dùng',
            'kpi' => 'KPI',
            'phancong' => 'Phân công KPI'
        ];
        return $objects[$this->Doi_tuong] ?? $this->Doi_tuong;
    }
}
