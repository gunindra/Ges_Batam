<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retur extends Model
{
    protected $table = 'tbl_retur';

    protected $fillable = [
        // 'no_retur',
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

}
