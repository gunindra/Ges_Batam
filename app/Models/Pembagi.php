<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembagi extends Model
{
    use HasFactory;

    protected $table = 'tbl_pembagi'; 
    protected $fillable = [
        'nilai_pembagi',
    ];
}
