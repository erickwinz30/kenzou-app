<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('badges', function (Blueprint $table) {
      $table->id();
      $table->string('nama');
      $table->integer('min_point');
      $table->decimal('discount', 3, 2);
      $table->string('image');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('badges');
  }
};
