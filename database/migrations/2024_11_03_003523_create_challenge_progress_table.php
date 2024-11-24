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
    Schema::create('challenge_progress', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->foreignUuid('challenge_id');
      $table->foreignUuid('member_id');
      $table->integer('progress')->default(0);
      $table->boolean('is_completed')->default(false);
      $table->boolean('is_used')->default(false);
      $table->datetime('completed_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('challenge_progress');
  }
};
