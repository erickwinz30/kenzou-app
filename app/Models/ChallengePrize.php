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

  public function layanans()
  {
    return $this->belongsTo(Layanan::class, 'layanan_id');
  }

  public function challenges()
  {
    return $this->hasMany(Challenge::class);
  }
}
