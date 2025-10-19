<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thongbao extends Model
{
    use HasFactory;

    protected $table = 'thongbao';
    protected $primaryKey = 'ID_thongbao';

    public $timestamps = false;

    protected $fillable = [
        'ID_nguoigui',
        'ID_nguoinhan',
        'Tieu_de',
        'Noi_dung',
        'Loai_thongbao',
        'Da_xem'
    ];

    public function nguoigui()
    {
        return $this->belongsTo(User::class, 'ID_nguoigui', 'ID_user');
    }

    public function nguoinhan()
    {
        return $this->belongsTo(User::class, 'ID_nguoinhan', 'ID_user');
    }
}
