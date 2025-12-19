<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtctHssvProfile extends Model
{
    protected $table = 'BANG_CTCTHSSV';
    protected $primaryKey = 'MaCTCT';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['MaCTCT', 'TenPhong', 'NguoiQL', 'MaTK'];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaTK', 'MaTK');
    }
}
