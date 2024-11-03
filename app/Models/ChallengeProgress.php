<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Challenge;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChallengeProgress extends Model
{
  use HasFactory;
  protected $guarded = ['id'];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = Str::uuid()->toString();
    });
  }

  public function challenge()
  {
    return $this->belongsTo(Challenge::class, 'challenge_id');
  }

  public function member()
  {
    return $this->belongsTo(Member::class, 'member_id');
  }
}
