<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
  use HasFactory;

  protected $keyType = 'string';
  public $incrementing = false;
  protected $guarded = ['id'];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = Str::uuid()->toString();
    });
  }

  public function ownedVouchers()
  {
    return $this->hasMany(OwnedVoucher::class, 'voucher_id');
  }
}
