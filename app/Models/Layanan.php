<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Layanan extends Model
{
  use HasFactory;
  protected $guarded = ['id'];

  public function transaksi()
  {
    return $this->hasMany(Transaksi::class);
  }

  public function layananLog()
  {
    return $this->hasMany(LayananLog::class);
  }

  public function detailLayanan()
  {
    return $this->hasMany(DetailLayanan::class, 'detail_layanan_id');
  }
}
