<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
  use HasFactory;
  protected $keyType = 'string';
  public $incrementing = false;

  protected $fillable = [
    'member_id',
    'subject',
    'description',
    'is_read',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->{$model->getKeyName()} = Str::uuid()->toString();
    });
  }

  public function member()
  {
    return $this->belongsTo(Member::class, 'member_id');
  }
}
