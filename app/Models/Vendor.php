<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'tbl_vendors';

    protected $fillable = ['name', 'address', 'phone', 'account_id','company_id'];

    public function account()
    {
        return $this->belongsTo(COA::class, 'account_id');
    }
}


