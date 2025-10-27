<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DulieuTask extends Model
{
    use HasFactory;

    protected $table = 'dulieu_task';
    protected $primaryKey = 'ID_dulieu';

    protected $fillable = [
        'task_id',
        'user_id',
        'Minh_chung',
        'File_name',
        'File_path',
        'Ngay_gui'
    ];

    protected $casts = [
        'Ngay_gui' => 'datetime'
    ];

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id', 'ID_task');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'ID_user');
    }
}

