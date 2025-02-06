<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_pembeli';
    protected $fillable = [
        'marking',
        'nama_pembeli',
        'no_wa',
        'sisa_poin',
        'category_id',
        'transaksi_terakhir',
        'status',
        'non_active_at',
        'metode_pengiriman',
        'user_id',
        'company_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    protected $dates = [
        'transaksi_terakhir',
        'non_active_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'pembeli_id');
    }
}
