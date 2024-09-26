<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use HasFactory;

    protected $table = 'tbl_popup'; 
    protected $fillable = [
        'title_Popup',
        'Paragraph_Popup',
        'Link_Popup',
        'Image_Popup',
    ];
}
