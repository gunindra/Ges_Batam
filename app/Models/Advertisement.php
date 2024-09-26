<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $table = 'tbl_advertisement'; 
    protected $fillable = [
        'title_Advertisement',
        'image_Advertisement',
        
    ];
}
