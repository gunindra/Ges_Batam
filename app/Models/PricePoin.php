<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricePoin extends Model
{
    use HasFactory;

    protected $table = 'tbl_price_poin';

    protected $fillable = [
        'price_per_kg',
        'effective_date',
    ];

    // Relationship to top-ups that use this price
    public function historyTopups()
    {
        return $this->hasMany(HistoryTopup::class, 'price_per_kg_id');
    }
}
