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
        'Ngay_giao',
        'Ngay_het_han'
    ];

    protected $casts = [
        'Ngay_giao' => 'datetime',
        'Ngay_het_han' => 'date'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id', 'ID_task', 'ID_user')
            ->withPivot('trang_thai', 'comment')
            ->withTimestamps();
    }

    public function submissions()
    {
        return $this->hasMany(DulieuTask::class, 'task_id', 'ID_task');
    }
}
