<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuKienAnh extends Model
{
    protected $table = 'BANG_SuKien_Anh';
    protected $primaryKey = 'MaAnh';
    public $timestamps = false;

    protected $fillable = ['MaSK','DuongDan','TenFile','ThuTu','TaoLuc'];
}
