<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuKien extends Model
{
    protected $table = 'BANG_SuKien';
    protected $primaryKey = 'MaSK';
    public $timestamps = false; // vì bạn dùng TaoLuc/CapNhatLuc, không phải created_at/updated_at

    protected $fillable = [
        'TieuDe','NoiDung','ThoiGianBatDau','ThoiGianKetThuc','DiaDiem',
        'SoLuongToiDa','TrangThai','TaoLuc','CapNhatLuc'
    ];

    public function anhs()
    {
        return $this->hasMany(SuKienAnh::class, 'MaSK', 'MaSK');
    }
}
