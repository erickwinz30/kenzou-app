<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OwnedVoucher extends Model
{
  use HasFactory;

  protected $keyType = 'string';
  protected $guarded = ['id'];
  protected $with = ['member', 'voucher'];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = Str::uuid()->toString();
    });
  }

  public function member()
  {
    return $this->belongsTo(Member::class, 'member_id');
  }

  public function voucher()
  {
    return $this->belongsTo(Voucher::class, 'voucher_id');
  }

  public function transaksi()
  {
    return $this->hasOne(Transaksi::class, 'owned_voucher_id');
  }
}
