<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryLayanan extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
  ];

  public function layanans()
  {
    return $this->hasMany(Layanan::class, 'category_layanan_id');
  }
}
