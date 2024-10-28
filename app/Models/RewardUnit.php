<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardUnit extends Model
{
  use HasFactory;

  protected $guarded = ['id'];

  public function challenges()
  {
    return $this->hasMany(Challenge::class, 'reward_unit_id');
  }
}
