<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKySuKien extends Model
{
    protected $table = 'BANG_DangKySuKien';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null; // khóa chính kép

    protected $fillable = [
        'MaSK','MaSV','DangKyLuc','TrangThaiDangKy',
        'DaDiemDanh','DiemDanhLuc','GhiChu'
    ];
}
