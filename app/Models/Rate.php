<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $table = 'tbl_rate'; 
    protected $fillable = [
        'nilai_rate',
        'rate_for',
    ];
}
