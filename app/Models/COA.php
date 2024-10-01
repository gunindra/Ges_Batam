<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class COA extends Model
{
    // Nama tabel (opsional jika berbeda dari default 'coas')
    protected $table = 'tbl_coa';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'code_account_id',
        'parent_id',
        'group_account',
        'name',
        'description',
        'set_as_group',
        'default_posisi'
    ];

    // Relasi ke parent
    public function parent()
    {
        return $this->belongsTo(COA::class, 'parent_id');
    }

    // Relasi ke child
    public function children()
    {
        return $this->hasMany(COA::class, 'parent_id');
    }
}
