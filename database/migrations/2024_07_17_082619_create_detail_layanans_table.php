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
      $table->foreignUuid('transaksi_id');
      $table->foreignId('layanan_id');
      $table->timestamps();
      //pertimbangkan kolom total_point yang akan diterima oleh pelanggan
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
