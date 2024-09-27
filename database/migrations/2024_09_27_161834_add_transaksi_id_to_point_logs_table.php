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
    Schema::table('point_logs', function (Blueprint $table) {
      $table->foreignUuid('transaksi_id')->constrained('transaksis')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('point_logs', function (Blueprint $table) {
      Schema::dropIfExists('point_logs');
    });
  }
};
