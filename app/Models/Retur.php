<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retur extends Model
{
    protected $table = 'tbl_retur';

    protected $fillable = [
        'invoice_id',
        'currency_id',
        'account_id',
        'total_nominal',
        'deskripsi',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ReturItem::class, 'retur_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function currency()
    {
        return $this->belongsTo(MataUang::class, 'currency_id');
    }

    public function account()
    {
        return $this->belongsTo(COA::class, 'account_id');
    }
}
