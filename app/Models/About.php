<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class About extends Model
{
    use HasFactory;
    protected $table = 'tbl_aboutus'; 
    protected $fillable = [
        'Paragraph_AboutUs',
        'Image_AboutUs',
    ];
}
