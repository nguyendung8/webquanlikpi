<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'ID_task';

    public $timestamps = false;

    protected $fillable = [
        'Ten_task',
        'Mo_ta',
        'ID_user_duocgiao',
        'Trang_thai',
        'Ngay_giao',
        'Ngay_het_han'
    ];

    protected $casts = [
        'Ngay_giao' => 'datetime',
        'Ngay_het_han' => 'date'
    ];

    public function nguoiDuocGiao()
    {
        return $this->belongsTo(User::class, 'ID_user_duocgiao', 'ID_user');
    }
}
