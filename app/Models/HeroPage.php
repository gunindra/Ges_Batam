<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroPage extends Model
{
    use HasFactory;

    protected $table = 'tbl_heropage'; 
    protected $fillable = [
        'title_heropage',
        'image_heropage',
    ];
}
