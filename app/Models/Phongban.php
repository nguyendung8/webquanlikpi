<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phongban extends Model
{
    use HasFactory;

    protected $table = 'phongban';
    protected $primaryKey = 'ID_phongban';

    // Tắt timestamps vì table không có created_at, updated_at
    public $timestamps = false;

    protected $fillable = [
        'Ten_phongban'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'ID_phongban', 'ID_phongban');
    }
}
