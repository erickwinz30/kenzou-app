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
    Schema::create('layanans', function (Blueprint $table) {
      $table->id();
      $table->string('nama_layanan');
      $table->decimal('harga', 10, 2);
      $table->integer('point');
      $table->string('detail');
      $table->boolean('is_active')->default(true);
      $table->foreignId('category_layanan_id')->constrained('category_layanans');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('layanans');
  }
};
