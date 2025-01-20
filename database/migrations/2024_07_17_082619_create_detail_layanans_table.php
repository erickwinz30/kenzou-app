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
    Schema::create('detail_layanans', function (Blueprint $table) {
      $table->id();
      $table->foreignUuid('transaksi_id')->constrained('transaksis');
      $table->foreignId('layanan_id')->constrained('layanans');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('detail_layanans');
  }
};
