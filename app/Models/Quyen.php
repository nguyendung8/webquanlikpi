<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quyen extends Model
{
    use HasFactory;

    protected $table = 'quyen';
    protected $primaryKey = 'ID_quyen';

    // Tắt timestamps vì table không có created_at, updated_at
    public $timestamps = false;

    protected $fillable = [
        'Ten_quyen'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'ID_quyen', 'ID_quyen');
    }
}
