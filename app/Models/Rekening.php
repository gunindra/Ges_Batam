<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    protected $table = 'tbl_rekening'; 
    protected $fillable = [
        'pemilik',
        'nomer_rekening',
        'nama_bank',
    ];
}
