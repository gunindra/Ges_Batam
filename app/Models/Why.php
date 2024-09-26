<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Why extends Model
{
    use HasFactory;
    protected $table = 'tbl_whyus'; 
    protected $fillable = [
        'Image_WhyUs',
        'Paragraph_WhyUs',
       
    ];
}
