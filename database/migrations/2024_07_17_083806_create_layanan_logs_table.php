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
    Schema::create('layanan_logs', function (Blueprint $table) {
      // $table->id();
      $table->foreignId('layanan_id');
      $table->string('nama_layanan');
      $table->decimal('harga');
      $table->integer('point');
      $table->string('detail');
      $table->datetime('deleted_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('layanan_logs');
  }
};
