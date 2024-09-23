<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
  use HasFactory;

  protected $keyType = 'string';
  protected $guarded = ['id'];
  protected $with = ['pelanggan', 'layanan', 'user', 'detail_layanan'];

  public $incrementing = false;

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = Str::uuid()->toString();
    });
  }

  public function pelanggan()
  {
    return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
  }

  public function layanan()
  {
    return $this->belongsTo(Layanan::class, 'layanan_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function detail_layanan()
  {
    return $this->hasMany(DetailLayanan::class);
  }
}
