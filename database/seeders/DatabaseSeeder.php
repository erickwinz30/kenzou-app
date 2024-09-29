<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Layanan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

    User::factory()->create([
      'nama' => 'Admin',
      'username' => 'admin',
      'email' => 'admin@example.com',
      'nomor_telepon' => '081234567890',
      'is_admin' => 1,
      'password' => bcrypt('admin123'),
    ]);

    User::factory()->create([
      'nama' => 'Hanyang',
      'username' => 'hanyang123',
      'email' => 'hanyang@gmail.com',
      'nomor_telepon' => '089664437785',
      'is_admin' => 0,
      'password' => bcrypt('hanyang123'),
    ]);

    Layanan::factory()->create([
      'nama_layanan' => 'Cuci Eksterior',
      'harga' => '60000',
      'point' => 2,
      'added_date' => Carbon::now('Asia/Jakarta'),
      'detail' => 'Pencucian eksterior dengan cuci manual dan menggunakan robot',
    ]);

    Layanan::factory()->create([
      'nama_layanan' => 'Cuci Interior',
      'harga' => '40000',
      'point' => 2,
      'added_date' => Carbon::now('Asia/Jakarta'),
      'detail' => 'Pembersihan interior dengan cairan pembersih dan vacuum',
    ]);

    Layanan::factory()->create([
      'nama_layanan' => 'Wax',
      'harga' => '70000',
      'point' => 5,
      'added_date' => Carbon::now('Asia/Jakarta'),
      'detail' => 'Pemberian cairan obat untuk cat pada mobil',
    ]);

    Layanan::factory()->create([
      'nama_layanan' => 'Fogging',
      'harga' => '35000',
      'point' => 3,
      'added_date' => Carbon::now('Asia/Jakarta'),
      'detail' => 'Pemberian asap/uap disinfektan',
    ]);
  }
}
