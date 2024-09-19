<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;
  protected $keyType = 'string';
  protected $guarded = ['id'];
  protected $with = ['pelanggan'];

  protected $fillable = [
    'nama',
    'email',
    'nomor_telepon',
    'tanggal_lahir',
    'experience_point',
    'redeemable_point',
    'google_id',
    'referral_code',
    'password',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = (string) Str::uuid();
    });
  }

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  public function pelanggan()
  {
    return $this->hasOne(Pelanggan::class, 'member_id', 'id');
  }
}
