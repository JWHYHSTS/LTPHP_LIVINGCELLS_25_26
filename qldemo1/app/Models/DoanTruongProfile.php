<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoanTruongProfile extends Model
{
    protected $table = 'BANG_DoanTruong';
    protected $primaryKey = 'MaDT';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['MaDT', 'TenDT', 'NguoiQL', 'MaTK'];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'MaTK', 'MaTK');
    }
}
