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
    Schema::create('transaksis', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->foreignUuid('pelanggan_id');
      $table->foreignUuid('user_id');
      $table->foreignUuid('voucher_id')->nullable();
      $table->foreignUuid('challenge_id')->nullable();
      $table->foreignId('badge_id')->nullable();
      $table->foreignId('leaderboard_id')->nullable();
      $table->datetime('date');
      $table->decimal('total');
      $table->decimal('subtotal');
      $table->string('metode_pembayaran');
      $table->string('keterangan')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transaksis');
  }
};
