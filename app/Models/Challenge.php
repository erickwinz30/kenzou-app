<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Challenge extends Model
{
  use HasFactory;

  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'description',
    'from_date',
    'to_date',
    'is_repeatable',
    'target',
    'unit',
    'layanan_id',
    'is_active',
  ];


  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = Str::uuid()->toString();
    });
  }

  public function challenge_progress()
  {
    return $this->hasMany(ChallengeProgress::class, 'challenge_id');
  }

  public function layanan()
  {
    return $this->belongsTo(Layanan::class, 'layanan_id');
  }
}
