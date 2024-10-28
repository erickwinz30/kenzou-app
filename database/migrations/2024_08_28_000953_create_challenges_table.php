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
    Schema::create('challenges', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('description');
      $table->datetime('from_date');
      $table->datetime('to_date');
      $table->integer('target');
      $table->foreignId('reward_unit_id');
      $table->boolean('is_active')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('challenges');
  }
};
