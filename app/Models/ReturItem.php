<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturItem extends Model
{
    protected $table = 'tbl_retur_item';

    protected $fillable = [
        'retur_id',
        'resi_id',
    ];

    public function retur(): BelongsTo
    {
        return $this->belongsTo(Retur::class, 'retur_id');
    }
}
