<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Badge;
use App\Models\Layanan;
use App\Models\Voucher;
use App\Models\LayananLog;
use App\Models\ChallengePrize;
use App\Models\CategoryLayanan;
use Illuminate\Database\Seeder;
use App\Models\BadgeLeaderboard;
use Illuminate\Support\Facades\Storage;

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
        'harga' => '80000',
        'point' => 2,
        'detail' => 'Pencucian eksterior dengan cuci manual dan menggunakan robot',
        'category_layanan_id' => 1,
      ],
      [
        'nama_layanan' => 'Cuci Interior',
        'harga' => '40000',
        'point' => 2,
        'detail' => 'Pembersihan interior dengan cairan pembersih dan vacuum',
        'category_layanan_id' => 1,
      ],
      [
        'nama_layanan' => 'Wax',
        'harga' => '120000',
        'point' => 5,
        'detail' => 'Pemberian cairan obat untuk cat pada mobil',
        'category_layanan_id' => 2,
      ],
      [
        'nama_layanan' => 'Fogging',
        'harga' => '50000',
        'point' => 3,
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

    // Path lokal gambar
    $localPaths = [
      'bronze' => 'D:\Badges\bronze.png',
      'silver' => 'D:\Badges\silver.png',
      'gold' => 'D:\Badges\gold.png',
    ];

    // Path penyimpanan di aplikasi
    $storagePaths = [];

    foreach ($localPaths as $key => $localPath) {
      // Salin gambar ke direktori penyimpanan aplikasi
      $storagePath = 'badge-image/' . basename($localPath);
      Storage::disk('public')->put($storagePath, file_get_contents($localPath));
      $storagePaths[$key] = $storagePath;
    }

    $badges = [
      [
        'nama' => 'Bronze',
        'min_point' => 0,
        'max_point' => 499,
        'discount' => 0,
        'image' => $storagePaths['bronze'],
      ],
      [
        'nama' => 'Silver',
        'min_point' => 500,
        'max_point' => 1499,
        'discount' => 0.03,
        'image' => $storagePaths['silver'],
      ],
      [
        'nama' => 'Gold',
        'min_point' => 1500,
        'max_point' => 100000,
        'discount' => 0.05,
        'image' => $storagePaths['gold'],
      ],
    ];

    foreach ($badges as $badge) {
      Badge::create($badge);
    }

    // Path lokal gambar
    $localPathsLeaderboard = [
      'bronze' => 'D:\Badges\Leaderboard\3.png',
      'silver' => 'D:\Badges\Leaderboard\2.png',
      'gold' => 'D:\Badges\leaderboard\1.png',
    ];

    // Path penyimpanan di aplikasi
    $storagePathLeaderboards = [];

    foreach ($localPathsLeaderboard as $key => $localPathLeaderboard) {
      // Salin gambar ke direktori penyimpanan aplikasi
      $storagePathLeaderboard = 'badge-leaderboard-image/' . basename($localPathLeaderboard);
      Storage::disk('public')->put($storagePathLeaderboard, file_get_contents($localPathLeaderboard));
      $storagePathLeaderboards[$key] = $storagePathLeaderboard;
    }

    $badgeLeaderboards = [
      [
        'badge_name' => 'Bronze',
        'rank' => 3,
        'discount' => 0,
        'image' => $storagePathLeaderboards['bronze'],
      ],
      [
        'badge_name' => 'Silver',
        'rank' => 2,
        'discount' => 0.05,
        'image' => $storagePathLeaderboards['silver'],
      ],
      [
        'badge_name' => 'Gold',
        'rank' => 1,
        'discount' => 0.1,
        'image' => $storagePathLeaderboards['gold'],
      ],
    ];

    foreach ($badgeLeaderboards as $badge) {
      BadgeLeaderboard::create($badge);
    }

    $vouchers = [
      [
        'nama' => 'Voucher Diskon 5%',
        'description' => 'Mendapatkan diskon 5% pada saat melakukan pencucian mobil',
        'point_needed' => 50,
        'discount' => 0.05,
        'minimum_transaction' => 50000,
        'from_date' => Carbon::create(2024, 1, 1, 0, 0, 0),
        'to_date' => Carbon::create(2025, 12, 31, 23, 59, 59),
      ],
      [
        'nama' => 'Voucher Diskon 10%',
        'description' => 'Mendapatkan diskon 10% pada saat melakukan pencucian mobil',
        'point_needed' => 100,
        'discount' => 0.1,
        'minimum_transaction' => 10000,
        'from_date' => Carbon::create(2024, 1, 1, 0, 0, 0),
        'to_date' => Carbon::create(2025, 12, 31, 23, 59, 59),
      ],
      [
        'nama' => 'Voucher Diskon 20%',
        'description' => 'Mendapatkan diskon 20% pada saat melakukan pencucian mobil',
        'point_needed' => 150,
        'discount' => 0.2,
        'minimum_transaction' => 140000,
        'from_date' => Carbon::create(2024, 1, 1, 0, 0, 0),
        'to_date' => Carbon::create(2025, 12, 31, 23, 59, 59),
      ],
    ];

    foreach ($vouchers as $voucher) {
      Voucher::create($voucher);
    }
  }
}
