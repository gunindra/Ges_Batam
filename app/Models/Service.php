<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'tbl_service'; 
    protected $fillable = [
        'title_service',
        'content_service',
        'image_service',
    ];
}
