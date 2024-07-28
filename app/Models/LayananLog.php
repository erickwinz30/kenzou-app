<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananLog extends Model
{
    use HasFactory;

    protected $with = ['layanan'];

    protected $fillable = [
        'layanan_id',
        'nama_layanan',
        'harga',
        'detail',
        'added_date',
        'updated_date',
        'deleted_date',
    ];

    public function layanan() {
        return $this->belongsTo(Layanan::class);
    }
}
