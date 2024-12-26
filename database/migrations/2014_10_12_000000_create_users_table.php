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
    Schema::create('users', function (Blueprint $table) {
      $table->uuid('id')->primary();
      $table->string('nama');
      $table->string('username')->unique();
      $table->string('email')->unique()->nullable();
      $table->string('nomor_telepon')->unique();
      $table->boolean('is_admin');
      $table->datetime('email_verified_at')->nullable();
      $table->string('password');
      $table->boolean('is_active')->default(true);
      $table->rememberToken();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
