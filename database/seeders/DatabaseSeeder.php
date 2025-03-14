<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Badge;
use App\Models\Member;
use App\Models\Layanan;
use App\Models\Voucher;
use App\Models\PointLog;
use App\Models\Challenge;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\LayananLog;
use Illuminate\Support\Str;
use App\Models\DetailLayanan;
use App\Models\CategoryLayanan;
use Illuminate\Database\Seeder;
use App\Models\BadgeLeaderboard;
use App\Models\ChallengeProgress;
use Illuminate\Support\Facades\Log;
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
        'point' => 3,
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
        'point' => 1,
        'detail' => 'Pemberian asap/uap disinfektan',
        'category_layanan_id' => 3,
      ],
    ];

    foreach ($layanans as $layananData) {
      $layanan = Layanan::factory()->create($layananData);
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
        'discount' => 0.03,
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
        'from_date' => Carbon::create(2024, 1, 1, 0, 0, 0)->toIso8601String(),
        'to_date' => Carbon::create(2025, 12, 31, 23, 59, 59)->toIso8601String(),
        'is_active' => 1,
      ],
      [
        'nama' => 'Voucher Diskon 10%',
        'description' => 'Mendapatkan diskon 10% pada saat melakukan pencucian mobil',
        'point_needed' => 100,
        'discount' => 0.1,
        'minimum_transaction' => 100000,
        'from_date' => Carbon::create(2024, 1, 1, 0, 0, 0)->toIso8601String(),
        'to_date' => Carbon::create(2025, 12, 31, 23, 59, 59)->toIso8601String(),
        'is_active' => 1,
      ],
      [
        'nama' => 'Voucher Diskon 20%',
        'description' => 'Mendapatkan diskon 20% pada saat melakukan pencucian mobil',
        'point_needed' => 150,
        'discount' => 0.2,
        'minimum_transaction' => 140000,
        'from_date' => Carbon::create(2024, 1, 1, 0, 0, 0)->toIso8601String(),
        'to_date' => Carbon::create(2025, 12, 31, 23, 59, 59)->toIso8601String(),
        'is_active' => 1,
      ],
    ];

    foreach ($vouchers as $voucher) {
      Voucher::create($voucher);
    }

    $members = [
      [
        'nama' => 'Miyawaki Sakura',
        'email' => 'miyawakisakura@gmail.com',
        'nomor_telepon' => '086473829177',
        'tanggal_lahir' => Carbon::create(1998, 3, 19),
        'experience_point' => 1940,
        'redeemable_point' => 1940,
        'password' => bcrypt('sakura123'),
      ],
      [
        'nama' => 'Huh Yunjin',
        'email' => 'huh.yunjin@gmail.com',
        'nomor_telepon' => '084388749990',
        'tanggal_lahir' => Carbon::create(2001, 10, 8),
        'experience_point' => 1247,
        'redeemable_point' => 1240,
        'password' => bcrypt('yunjin123'),
      ],
      [
        'nama' => 'Kim Chaewon',
        'email' => 'kimchaewon@gmail.com',
        'nomor_telepon' => '088999643332',
        'tanggal_lahir' => Carbon::create(2000, 10, 1),
        'experience_point' => 780,
        'redeemable_point' => 780,
        'password' => bcrypt('chaewon123'),
      ],
      [
        'nama' => 'Song Hayoung',
        'email' => 'songhayoung@gmail.com',
        'nomor_telepon' => '081343256643',
        'tanggal_lahir' => Carbon::create(1997, 9, 29),
        'experience_point' => 450,
        'redeemable_point' => 450,
        'password' => bcrypt('hayoung123'),
      ],
    ];

    foreach ($members as $member) {
      // $memberId = Layanan::where('nama_layanan', $layananData['nama_layanan'])->first()->id;
      $referralCode = strtoupper(Str::random(8));

      // Optionally, filter out lowercase letters (if they appear)
      $referralCode = preg_replace('/[^A-Z0-9]/', '', $referralCode);

      $member['referral_code'] = $referralCode;

      $registeredMember = Member::create($member);

      Pelanggan::create([
        'nomor_telepon' => $member['nomor_telepon'],
        'member_id' => $registeredMember->id,
      ]);
    }

    $challenges = [
      [
        'description' => "Melakukan pencucian sebanyak 8 kali",
        'from_date' => Carbon::create(2024, 1, 1, 0, 0, 0)->toIso8601String(),
        'to_date' => Carbon::create(2025, 12, 31, 23, 59, 59)->toIso8601String(),
        'target' => 8,
        'unit' => 'Transaksi',
        'layanan_id' => 4,
        'is_repeatable' => 1,
        'is_active' => 1,
      ],
      // [
      //   'description' => "Melakukan akumulasi transaksi sebesar Rp. 800000",
      //   'from_date' => Carbon::create(2024, 1, 1, 0, 0, 0)->toIso8601String(),
      //   'to_date' => Carbon::create(2025, 12, 31, 23, 59, 59)->toIso8601String(),
      //   'target' => 800000,
      //   'unit' => 'Total Pengeluaran Member',
      //   'layanan_id' => 1,
      //   'is_repeatable' => 0,
      //   'is_active' => 1,
      // ],
    ];

    foreach ($challenges as $dataChallenge) {
      $challenge = Challenge::create($dataChallenge);
      Log::info($challenge);

      foreach ($members as $member) {
        $memberId = Member::where('nama', $member['nama'])->first()->id;
        Log::info($memberId);
        $createdChallengeProgress = ChallengeProgress::create([
          'member_id' => $memberId,
          'challenge_id' => $challenge->id,
        ]);

        Log::info($createdChallengeProgress);
      }
    }

    $this->createTransaksiSeeds();
  }

  private function createTransaksiSeeds()
  {
    $startDate = Carbon::create(2025, 1, 1)->startOfDay();
    $endDate = Carbon::create(2025, 3, 31)->endOfDay();

    $pelanggans = Pelanggan::all();
    $users = User::where('is_admin', 1)->get();
    $layanans = Layanan::all();

    Log::info('Starting transaction seeding');
    Log::info('Pelanggan count: ' . $pelanggans->count());
    Log::info('User count: ' . $users->count());
    Log::info('Layanan count: ' . $layanans->count());

    while ($startDate <= $endDate) {
      $transactionCount = rand(0, 5); // 0 to 5 transactions per day

      Log::info('Creating ' . $transactionCount . ' transactions for date: ' . $startDate->toDateString());

      for ($i = 0; $i < $transactionCount; $i++) {
        $pelanggan = $pelanggans->random();
        $user = $users->random();

        // Select random services and calculate total before creating the transaction
        $selectedLayanans = $layanans->random(rand(1, 3)); // 1 to 3 services per transaction
        $subtotal = $selectedLayanans->sum('harga');
        $total = $subtotal; // Assuming no discounts for simplicity

        $transaksi = Transaksi::create([
          'id' => Str::uuid(),
          'pelanggan_id' => $pelanggan->id,
          'user_id' => $user->id,
          'date' => $this->getRandomTimeWithinOperatingHours($startDate),
          'nomor_polisi' => $this->generateRandomPlateNumber(),
          'metode_pembayaran' => $this->getRandomPaymentMethod(),
          'is_paid_off' => true,
          'keterangan' => null,
          'subtotal' => $subtotal,
          'total' => $total,
        ]);

        Log::info('Created transaction', ['transaksi' => $transaksi]);

        // Create DetailLayanan records
        foreach ($selectedLayanans as $layanan) {
          DetailLayanan::create([
            'transaksi_id' => $transaksi->id,
            'layanan_id' => $layanan->id,
          ]);
        }

        // Calculate total points for the transaction
        $totalPoint = $selectedLayanans->sum('point');

        // Create PointLog if the pelanggan is a member
        if ($pelanggan->member_id) {
          $pointLog = PointLog::create([
            'member_id' => $pelanggan->member_id,
            'point' => $totalPoint,
            'transaksi_id' => $transaksi->id,
            'status' => 'Transaksi',
            'date' => Carbon::now('Asia/Jakarta'),
            'is_increase' => true,
          ]);

          Log::info('Created PointLog', ['pointLog' => $pointLog]);
        }
      }

      $startDate->addDay();
    }

    Log::info('Finished transaction seeding');
  }

  private function getRandomTimeWithinOperatingHours(Carbon $date)
  {
    $openingTime = 8; // 8 AM
    $closingTime = 17; // 5 PM

    $randomHour = rand($openingTime, $closingTime - 1);
    $randomMinute = rand(0, 59);

    return $date->copy()->setHour($randomHour)->setMinute($randomMinute)->setSecond(0);
  }

  private function generateRandomPlateNumber()
  {
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';

    return substr(str_shuffle($letters), 0, 1) .
      substr(str_shuffle($numbers), 0, 4) .
      substr(str_shuffle($letters), 0, 2);
  }

  private function getRandomPaymentMethod()
  {
    $methods = ['qris', 'tunai'];
    return $methods[array_rand($methods)];
  }
}
