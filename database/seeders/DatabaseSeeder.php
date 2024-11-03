<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Layanan;
use App\Models\LayananLog;
use App\Models\CategoryLayanan;
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

    $categories = [
      [
        'name' => 'Cuci',
      ],
      [
        'name' => 'Detailing',
      ],
      [
        'name' => 'Lainnya',
      ]
    ];

    foreach ($categories as $category) {
      CategoryLayanan::factory()->create($category);
    }


    $layanans = [
      [
        'nama_layanan' => 'Cuci Eksterior',
        'harga' => '60000',
        'point' => 2,
        'added_date' => Carbon::now('Asia/Jakarta'),
        'detail' => 'Pencucian eksterior dengan cuci manual dan menggunakan robot',
        'category_layanan_id' => 1,
      ],
      [
        'nama_layanan' => 'Cuci Interior',
        'harga' => '40000',
        'point' => 2,
        'added_date' => Carbon::now('Asia/Jakarta'),
        'detail' => 'Pembersihan interior dengan cairan pembersih dan vacuum',
        'category_layanan_id' => 1,
      ],
      [
        'nama_layanan' => 'Wax',
        'harga' => '70000',
        'point' => 5,
        'added_date' => Carbon::now('Asia/Jakarta'),
        'detail' => 'Pemberian cairan obat untuk cat pada mobil',
        'category_layanan_id' => 2,
      ],
      [
        'nama_layanan' => 'Fogging',
        'harga' => '35000',
        'point' => 3,
        'added_date' => Carbon::now('Asia/Jakarta'),
        'detail' => 'Pemberian asap/uap disinfektan',
        'category_layanan_id' => 3,
      ],
    ];

    foreach ($layanans as $layananData) {
      $layanan = Layanan::factory()->create($layananData);
      $layananId = Layanan::where('nama_layanan', $layananData['nama_layanan'])->first()->id;
      LayananLog::factory()->create([
        'layanan_id' => $layananId,
        'nama_layanan' => $layanan->nama_layanan,
        'harga' => $layanan->harga,
        'point' => $layanan->point,
        'detail' => $layanan->detail,
      ]);
    }

    // $member[] = [

    // ];
  }
}
