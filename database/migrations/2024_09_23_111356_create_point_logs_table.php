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
    Schema::create('point_logs', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->foreignUuid('transaksi_id')->nullable();
      $table->foreignUuid('member_id');
      $table->integer('point');
      $table->string('status');
      $table->boolean('is_increase');
      $table->foreignUuid('point_from')->nullable();
      $table->dateTime('date');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('point_logs');
  }
};
