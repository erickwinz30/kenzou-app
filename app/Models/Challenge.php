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

  protected $fillable = [
    'description',
    'from_date',
    'to_date',
    'target',
    'unit_id',
    'reward_type',
    'reward_value',
    'is_active',
  ];


  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = Str::uuid()->toString();
    });
  }

  public function unit()
  {
    return $this->belongsTo(Unit::class, 'unit_id');
  }

  public function progress()
  {
    return $this->hasMany(ChallengeProgress::class, 'challenge_id');
  }
}
