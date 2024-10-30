<?php

namespace App\Models;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
  use HasFactory;

  protected $guarded = ['id'];

  public function challenges()
  {
    return $this->hasMany(Challenge::class, 'unit_id');
  }
}
