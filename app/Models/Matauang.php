<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matauang extends Model
{
    use HasFactory;
    protected $table = 'tbl_matauang';
    protected $fillable = [
        'nama_matauang',
        'singkatan_matauang',
    ];
}
