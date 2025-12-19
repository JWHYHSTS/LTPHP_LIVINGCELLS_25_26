<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhaoThiProfile extends Model
{
    protected $table = 'BANG_KhaoThi';
    protected $primaryKey = 'MaPKT';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['MaPKT', 'TenPhong', 'NguoiQL', 'MaTK'];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaTK', 'MaTK');
    }
}
