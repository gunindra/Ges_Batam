<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Money;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'tbl_assets';
    protected $fillable = [
        'asset_code',
        'asset_name',
        'acquisition_price',
        'acquisition_date',
        'depreciation_date',
        'residue_value',
        'estimated_age',
        'depreciation_account',
        'accumulated_account',
    ];
    protected $casts = [
        'acquisition_price' => Money::class,
        'residue_value' => Money::class,
    ];

}
