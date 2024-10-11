<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PTges extends Model
{
    use HasFactory;
    protected $table = 'tbl_ptges'; 
    protected $fillable = [
        'Paragraph_AboutUs',
        'Image_AboutUs',
        'Image_WhyUs',
        'Paragraph_WhyUs',
        'email',
        'phone',
        'phones',
    ];
}
