<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'ID_user';

    public $timestamps = false;

    protected $fillable = [
        'Email',
        'MK',
        'MK_hash',
        'Ho_ten',
        'ID_quyen',
        'ID_phongban',
        'Trang_thai'
    ];

    protected $hidden = [
        'MK',
        'MK_hash',
        'remember_token',
    ];

    protected $casts = [
        'Ngay_tao' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->MK_hash;
    }

    public function setMKHashAttribute($value)
    {
        if (!empty($value)) {
            if (password_get_info($value)['algo'] === null || password_get_info($value)['algo'] === 0) {
                $this->attributes['MK_hash'] = bcrypt($value);
            } else {
                $this->attributes['MK_hash'] = $value;
            }
        }
    }

    public function quyen()
    {
        return $this->belongsTo(Quyen::class, 'ID_quyen', 'ID_quyen');
    }

    public function phongban()
    {
        return $this->belongsTo(Phongban::class, 'ID_phongban', 'ID_phongban');
    }
}
