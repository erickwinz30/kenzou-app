<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
  use HasFactory;
  protected $keyType = 'string';
  protected $guarded = ['id'];
  protected $with = ['member'];


  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = Str::uuid()->toString();
    });
  }

  public function transaksi()
  {
    return $this->hasMany(Transaksi::class, 'pelanggan_id');
  }

  public function member()
  {
    return $this->belongsTo(Member::class, 'member_id', 'id');
  }
}
