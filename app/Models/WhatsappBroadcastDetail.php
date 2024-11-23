<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappBroadcastDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    
    public function broadcast()
    {
        return $this->belongsTo(WhatsappBroadcast::class);
    }
}
