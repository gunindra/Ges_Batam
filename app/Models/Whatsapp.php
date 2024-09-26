<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whatsapp extends Model
{
    use HasFactory;

    protected $table = 'tbl_wa'; 
    protected $fillable = [
        'No_wa',
        'Message_wa',
    ];
}
