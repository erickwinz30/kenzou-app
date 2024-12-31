<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointLog extends Model
{
  use HasFactory;

  protected $keyType = 'string';
  protected $guarded = ['id'];

  protected $fillable = [
    'transaksi_id',
    'member_id',
    'point',
    'status',
    'point_from',
    'is_increase',
    'date',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = Str::uuid()->toString();
    });
  }

  public function transaksi()
  {
    return $this->hasOne(Transaksi::class, 'transaksi_id', 'id');
  }

  public function member()
  {
    return $this->belongsTo(Member::class, 'member_id', 'id');
  }

  public function pointFrom()
  {
    return $this->belongsTo(Member::class, 'point_from');
  }
}
