<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;
  protected $keyType = 'string';
  protected $guarded = ['id'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'nama',
    'username',
    'email',
    'nomor_telepon',
    'is_admin',
    'password',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = (string) Str::uuid();
    });
  }

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  public function transaksi()
  {
    return $this->hasMany(Transaksi::class);
  }
}
