<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Information extends Model
{
    use HasFactory;

    protected $table = 'tbl_informations'; 
    protected $fillable = [
        'title_informations',
        'content_informations',
        'created_at',
        'updated_at',
    ];

    // Jika Anda ingin menggunakan soft deletes, aktifkan baris ini
    // use SoftDeletes;

    // Mendapatkan URL gambar
    public function getImageUrlAttribute()
    {
        return $this->image_informations ? Storage::url('images/' . $this->image_informations) : null;
    }
}