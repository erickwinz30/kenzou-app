<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengePrize extends Model
{
  use HasFactory;

  protected $fillable = [
    'layanan_id',
    'name',
  ];

  public function layanan()
  {
    return $this->belongsTo(Layanan::class);
  }

  public function challenges()
  {
    return $this->hasMany(Challenge::class);
  }
}
