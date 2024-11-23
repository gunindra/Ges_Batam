<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;
    protected $table = 'tbl_tracking';
    protected $fillable = [
        'no_resi',
        'no_do',
        'status',
        'keterangan',
    ];
}
