<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'tbl_periode'; 
    protected $fillable = [
        'id',
        'periode',
        'periode_start',
        'periode_end',
        'status',

    ];
}
