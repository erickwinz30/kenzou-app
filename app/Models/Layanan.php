<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Layanan extends Model
{
  use HasFactory;

  protected $with = ['categoryLayanan'];
  protected $fillable = [
    'nama_layanan',
    'harga',
    'point',
    'detail',
    'category_layanan_id',
  ];

  public function categoryLayanan()
  {
    return $this->belongsTo(CategoryLayanan::class, 'category_layanan_id');
  }

  public function transaksi()
  {
    return $this->hasMany(Transaksi::class);
  }

  public function detailLayanan()
  {
    return $this->hasMany(DetailLayanan::class, 'detail_layanan_id');
  }

  public function challenge()
  {
    return $this->hasMany(Challenge::class, 'layanan_id');
  }
}
