<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'tbl_alamat';

    protected $fillable = [
        'pembeli_id', 'alamat'
    ];

    public $timestamps = true;

    // Relasi ke Invoice
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'alamat_id', 'id');
    }
}
