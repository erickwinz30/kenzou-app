@extends('dashboard.layout.main')

@section('container')
<div class="pagetitle">
  <h1>Transaksi</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard/admin">Admin</a></li>
      <li class="breadcrumb-item"><a href="/dashboard/transaksi">Transaksi</a></li>
      <li class="breadcrumb-item"><a href="/dashboard/transaksi/{{ $transaksi->id }}/edit">Edit Transaksi</a></li>
    </ol>
  </nav>
</div><!-- End Page Title -->

@if(session()->has('error'))
<x-alert-error :message="session('error')" />
@endif

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Edit Transaksi</h5>
          <form action="/dashboard/transaksi/{{ $transaksi->id }}" method="POST">
            @method('put')
            @csrf
            <div class="mb-2 d-flex">
              <label for="id_transaksi" class="form-label me-3 @error('id_transaksi') is-invalid @enderror">ID
                Transaksi: </label>
              <p>{{ $transaksi->id }}</p>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div class="mb-3">
                <label for="user_id" class="form-label @error('user_id') is-invalid @enderror">Kasir</label>
                <select class="form-select" id="user_id" name="user_id">
                  @foreach ($users as $user)
                  @if(old('user_id', $transaksi->user->id) == $user->id)
                  <option value="{{ $user->id }}" selected>{{ $user->nama }}
                    {{ $user->is_active ? '' : '(Tidak aktif)' }}</option>
                  @else
                  <option value="{{ $user->id }}">{{ $user->nama }}
                    {{ $user->is_active ? '' : '(Tidak aktif)' }}</option>
                  @endif
                  @endforeach
                </select>
                @error('user_id')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="nomor_telepon" class="form-label @error('nomor_telepon') is-invalid @enderror">Informasi
                  Pelanggan</label>
                <p class="card-text">
                  {{ $transaksi->pelanggan->member_id
                  ? $transaksi->pelanggan->member->nama . ' (' . $transaksi->pelanggan->nomor_telepon . ')'
                  : $transaksi->pelanggan->nomor_telepon }}
                </p>
                @error('nomor_telepon')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2">
              <div class="mb-3">
                <label for="date" class="form-label @error('date') is-invalid @enderror">Tgl Transaksi</label>
                <input type="datetime-local" class="form-control" id="date" name="date"
                  value="{{ old('date', $transaksi->date) }}" required>
                @error('date')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="mb-3 mx-0">
                <p>Metode Pembayaran</p>
                <div class="d-flex justify-content-start align-items-center">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="exampleRadios1"
                      value="tunai" @if(old('metode_pembayaran', $transaksi->metode_pembayaran) == 'tunai') checked
                    @endif>
                    <label class="form-check-label" for="exampleRadios1">
                      Tunai
                    </label>
                  </div>
                  <div class="form-check ms-3">
                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="exampleRadios2"
                      value="qris" @if(old('metode_pembayaran', $transaksi->metode_pembayaran) == 'qris') checked
                    @endif>
                    <label class="form-check-label" for="exampleRadios2">
                      QRIS
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div class="mb-3">
                <label for="nomor_polisi" class="form-label @error('nomor_polisi') is-invalid @enderror">No. Polisi
                  Mobil</label>
                <input type="text" class="form-control" id="nomor_polisi" name="nomor_polisi" required autofocus
                  value="{{ old('nomor_polisi', $transaksi->nomor_polisi) }}">
                @error('nomor_polisi')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="keterangan" class="form-label @error('keterangan') is-invalid @enderror">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                  placeholder="Silahkan diisi disini..">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                @error('keterangan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            <div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="layanan" class="form-label @error('layanan') is-invalid @enderror">Layanan: </label>
                <div class="form-check form-switch d-flex justify-content-end align-items-center">
                  <p class="card-text m-0 me-5">Semua layanan: </p>
                  <input class="form-check-input" type="checkbox" role="switch" id="layanan-active-switch">
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3" id="layananContainer">
                @foreach ($layanans as $layanan)
                @if($layanan->is_active == 1)
                <div class="col">
                  <div class="card shadow h-85" style="border-radius: 15px">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                      <div>
                        <h5 class="card-title p-0">{{ $layanan->nama_layanan }}</h5>
                        <p class="card-text">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                      </div>
                      <div>
                        <button href="#" class="btn btn-primary add-item" data-layanan-id="{{ $layanan->id }}"
                          data-layanan-name="{{ $layanan->nama_layanan }}" data-layanan-price="{{ $layanan->harga }}"
                          @foreach ($detailLayanans as $detailLayanan) {{ $detailLayanan->layanan_id == $layanan->id ?
                          'disabled' : '' }} @endforeach>
                          <i class="bi bi-plus-circle"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                @endif
                @endforeach
              </div>
            </div>
            <div>
              <p class="card-text">Layanan yang terpilih</p>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3" id="selected-layanan-container">
                @foreach ($detailLayanans as $detailLayanan)
                <div class="col selected-layanan-item" data-layanan-id="{{ $detailLayanan->layanan->id }}"
                  data-layanan-name="{{ $detailLayanan->layanan->nama_layanan }}" @if($transaksi->challenge_progress_id)
                  data-layanan-price="{{ $transaksi->challenge_progress->challenge->layanan_id ===
                  $detailLayanan->layanan_id ? 0 :
                  $detailLayanan->layanan->harga }}"
                  @else
                  data-layanan-price="{{ $detailLayanan->layanan->harga }}" @endif
                  data-layanan-previous-price="{{ $detailLayanan->layanan->harga }}">
                  <div class="card shadow h-85" style="border-radius: 15px">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                      <div>
                        <h5 class="card-title p-0 m-0">
                          {{ $detailLayanan->layanan->nama_layanan }}
                          @if($transaksi->challenge_progress_id)
                          {!! $transaksi->challenge_progress->challenge->layanan_id === $detailLayanan->layanan_id
                          ? '<span style="color: green;">(Gratis)</span>'
                          : '' !!}
                          @endif
                        </h5>
                        <input type="hidden" name="layanan_id[]" value="{{ $detailLayanan->layanan->id }}">
                      </div>
                      <div>
                        <button href="#" class="btn btn-primary remove-item"
                          data-layanan-id="{{ $detailLayanan->layanan->id }}"
                          data-layanan-name="{{ $detailLayanan->layanan->nama_layanan }}"
                          @if($transaksi->challenge_progress_id)
                          data-layanan-price="{{ $transaksi->challenge_progress->challenge->layanan_id ===
                          $detailLayanan->layanan_id ? 0 :
                          $detailLayanan->layanan->harga }}"
                          @else data-layanan-price="{{ $detailLayanan->layanan->harga }}" @endif
                          data-layanan-previous-price="{{ $detailLayanan->layanan->harga }}">
                          <i class="bi bi-trash"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div class="mb-3"></div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <label for="total" class="form-label m-0 @error('total') is-invalid @enderror">Total: </label>
                <div class="input-group w-50">
                  <span class="input-group-text" id="mataUang">Rp</span>
                  <input type="text" inputmode="numeric" class="form-control" id="total" name="total"
                    aria-describedby="mataUang">
                  @error('total')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>

            @if(($usedVoucher ?? collect())->count() > 0 || ($OwnedVouchers ?? collect())->count() > 0)
            <p class="card-text">Voucher yang dimiliki member </p>
            @endif
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3" id="voucher-list-container">
              @if($transaksi->voucher_id)
              <div class="voucher-list" data-voucher-id="{{ $usedVoucher->voucher->id }}"
                data-voucher-name="{{ $usedVoucher->voucher->nama }}"
                data-voucher-discount="{{ $usedVoucher->voucher->discount }}"
                data-voucher-minimum-transaction="{{ $usedVoucher->voucher->minimum_transaction }}">
                <div class="card shadow h-85" style="border-radius: 15px">
                  <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                      <h5 class="card-title p-0">{{ $usedVoucher->voucher->nama }} <span
                          style="color: green;">(Digunakan)</span>
                      </h5>
                      <p class="card-text m-0">Diskon {{ $usedVoucher->voucher->discount * 100 }}%</p>
                      <p class="card-text m-0">Min Tran: Rp.
                        {{ number_format($usedVoucher->voucher->minimum_transaction, 0, ',', '.') }}
                      </p>
                    </div>
                    <div>
                      <button href="#" class="btn btn-primary voucher-add-item"
                        data-voucher-id="{{ $usedVoucher->voucher->id }}"
                        data-voucher-name="{{ $usedVoucher->voucher->nama }}"
                        data-voucher-discount="{{ $usedVoucher->voucher->discount }}"
                        data-voucher-minimum-transaction="{{ $usedVoucher->voucher->minimum_transaction }}">
                        <i class="bi bi-plus-circle"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              @endif
              @foreach ($ownedVouchers as $ownedVoucher)
              <div class="voucher-list" data-voucher-id="{{ $ownedVoucher->voucher->id }}"
                data-voucher-name="{{ $ownedVoucher->voucher->nama }}"
                data-voucher-discount="{{ $ownedVoucher->voucher->discount }}"
                data-voucher-minimum-transaction="{{ $ownedVoucher->voucher->minimum_transaction }}">
                <div class="card shadow h-85" style="border-radius: 15px">
                  <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                      <h5 class="card-title p-0">{{ $ownedVoucher->voucher->nama }}</h5>
                      <p class="card-text m-0">Diskon {{ $ownedVoucher->voucher->discount * 100 }}%</p>
                      <p class="card-text m-0">Min Tran: Rp.
                        {{ number_format($ownedVoucher->voucher->minimum_transaction, 0, ',', '.') }}
                      </p>
                    </div>
                    <div>
                      <button href="#" class="btn btn-primary voucher-add-item"
                        data-voucher-id="{{ $ownedVoucher->voucher->id }}"
                        data-voucher-name="{{ $ownedVoucher->voucher->nama }}"
                        data-voucher-discount="{{ $ownedVoucher->voucher->discount }}"
                        data-voucher-minimum-transaction="{{ $ownedVoucher->voucher->minimum_transaction }}">
                        <i class="bi bi-plus-circle"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>

            @if(($usedChallenge ?? collect())->count() > 0 || ($listChallenge ?? collect())->count() > 0)
            <p class="card-text">Challenge yang diselesaikan member </p>
            @endif
            <div id="challenge-list-container">
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                @if($transaksi->challenge_progress_id)
                <div class="challenge-list" data-challenge-id="{{ $usedChallenge->challenge->id }}"
                  data-challenge-description="{{ $usedChallenge->challenge->description }}"
                  data-challenge-free-layanan="{{ $usedChallenge->challenge->layanan_id }}"
                  data-challenge-free-layanan-name="{{ $usedChallenge->challenge->layanan->nama_layanan }}">
                  <div class="card shadow h-85" style="border-radius: 15px">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                      <div>
                        <h5 class="card-title p-0">{{ $usedChallenge->challenge->description }} <span
                            style="color: green;">(Digunakan)</span></h5>
                        <p class="card-text">Free {{ $usedChallenge->challenge->layanan->nama_layanan }}</p>
                      </div>
                      <div>
                        <button href="#" class="btn btn-primary challenge-add-item"
                          data-challenge-id="{{ $usedChallenge->challenge->id }}"
                          data-challenge-description="{{ $usedChallenge->challenge->description }}"
                          data-challenge-free-layanan="{{ $usedChallenge->challenge->layanan_id }}"
                          data-challenge-free-layanan-name="{{ $usedChallenge->challenge->layanan->nama_layanan }}">
                          <i class="bi bi-plus-circle"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                @endif
                @if ($listChallenge->count() > 0)
                @foreach ($listChallenge as $progressChallenge)
                <div class="challenge-list" data-challenge-id="{{ $progressChallenge->challenge->id }}"
                  data-challenge-description="{{ $progressChallenge->challenge->description }}"
                  data-challenge-free-layanan="{{ $progressChallenge->challenge->layanan_id }}"
                  data-challenge-free-layanan-name="{{ $progressChallenge->challenge->layanan->nama_layanan }}">
                  <div class="card shadow h-85" style="border-radius: 15px">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                      <div>
                        <h5 class="card-title p-0">{{ $progressChallenge->challenge->description }}</h5>
                        <p class="card-text">Free {{ $progressChallenge->challenge->layanan->nama_layanan }}</p>
                      </div>
                      <div>
                        <button href="#" class="btn btn-primary challenge-add-item"
                          data-challenge-id="{{ $progressChallenge->challenge->id }}"
                          data-challenge-description="{{ $progressChallenge->challenge->description }}"
                          data-challenge-free-layanan="{{ $progressChallenge->challenge->layanan_id }}"
                          data-challenge-free-layanan-name="{{ $progressChallenge->challenge->layanan->nama_layanan }}">
                          <i class="bi bi-plus-circle"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
                @endif
              </div>
            </div>
            @if ($transaksi->badge_id || $transaksi->leaderboard_id || $transaksi->voucher_id ||
            $transaksi->challenge_progress_id)
            @if ($transaksi->badge_id)
            <div id="badge-description-container">
              <div class="d-flex justify-content-between align-items-center mb-2" id="badge-description"
                data-badge-id="{{ $transaksi->badge->id }}" data-badge-discount="{{ $transaksi->badge->discount }}">
                <p class="m-0">Badge Member: {{ $transaksi->badge->nama }}</p>
                <p class="m-0" id="badge-description-value"></p>
              </div>
            </div>
            @else
            <div id="badge-description-container"></div>
            @endIf
            @if ($transaksi->leaderboard_id)
            <div id="leaderboard-description-container">
              <div class="d-flex justify-content-between align-items-center mb-2" id="leaderboard-description"
                data-leaderboard-id="{{ $transaksi->leaderboard->id }}"
                data-leaderboard-discount="{{ $transaksi->leaderboard->discount }}">
                <p class="m-0">Peringkat Member: {{ $transaksi->leaderboard->rank }}</p>
                <p class="m-0" id="leaderboard-description-value"></p>
              </div>
            </div>
            @else
            <div id="leaderboard-description-container"></div>
            @endIf
            @if ($transaksi->voucher_id)
            <div id="voucher-description-container">
              <div class="d-flex justify-content-between align-items-center mb-2" id="voucher-description"
                data-voucher-id="{{ $transaksi->voucher->id }}"
                data-voucher-discount="{{ $transaksi->voucher->discount }}">
                <input type="hidden" name="voucher_id" value="{{ $transaksi->voucher_id }}">
                <p class="m-0">{{ $transaksi->voucher->nama }}</p>
                <div class="d-flex justify-content-end align-items-center">
                  <p class="m-0" id="voucher-description-value"></p>
                  <button type="button" class="btn p-0 ms-2 my-auto" id="remove-voucher">
                    <i class="bi bi-x-circle"></i>
                  </button>
                </div>
              </div>
            </div>
            @else
            <div id="voucher-description-container"></div>
            @endIf
            @if ($transaksi->challenge_progress_id)
            <div id="challenge-description-container">
              <div class="d-flex justify-content-between align-items-center mb-2" id="challenge-description"
                data-challenge-id="{{ $transaksi->challenge_progress_id }}"
                data-challenge-free-layanan="{{ $transaksi->challenge_progress->challenge->layanan_id }}"
                data-challenge-free-layanan-name="{{ $transaksi->challenge_progress->challenge->layanan->nama_layanan }}"
                data-challenge-free-layanan-price="{{ $transaksi->challenge_progress->challenge->layanan->harga }}">
                <p class="m-0">{{ $transaksi->challenge_progress->challenge->description }}</p>
                <div class="d-flex justify-content-end align-items-center">
                  <input type="hidden" name="challenge_progress_id" value="{{ $transaksi->challenge_progress_id }}">
                  <p class="m-0">Gratis {{ $transaksi->challenge_progress->challenge->layanan->nama_layanan }}
                  </p>
                  <button type="button" class="btn p-0 ms-2 my-auto" id="remove-challenge">
                    <i class="bi bi-x-circle"></i>
                  </button>
                </div>
              </div>
            </div>
            @else
            <div id="challenge-description-container"></div>
            @endIf
            @endif
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 d-flex justify-content-end">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <label for="subtotal" class="form-label m-0 @error('subtotal') is-invalid @enderror">Subtotal:</label>
                <div class="input-group w-50">
                  <span class="input-group-text" id="mataUang">Rp</span>
                  <input type="text" inputmode="numeric" class="form-control" id="subtotal" name="subtotal"
                    aria-describedby="mataUang" required>
                  @error('subtotal')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="mb-3 mx-0 d-flex justify-content-between align-items-center">
              <p>Status Lunas: </p>
              @if ($transaksi->is_paid_off == 0)
              <div class="d-flex justify-content-start align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="is_paid_off" id="is_paid_off_tunai" value="0"
                    @if(old('is_paid_off', $transaksi->is_paid_off) == '0') checked @endif>
                  <label class="form-check-label" for="is_paid_off_tunai">
                    Belum Lunas
                  </label>
                </div>
                <div class="form-check ms-3">
                  <input class="form-check-input" type="radio" name="is_paid_off" id="is_paid_off_qris" value="1"
                    @if(old('is_paid_off', $transaksi->is_paid_off) == '1') checked @endif>
                  <label class="form-check-label" for="is_paid_off_qris">
                    Lunas
                  </label>
                </div>
              </div>
              @else
              <div>
                <input type="hidden" name="is_paid_off" value="1">
                <p>Lunas</p>
              </div>
              @endif
            </div>
            <div class="modal-footer">
              <a href="/dashboard/transaksi" class="btn btn-secondary me-1">Batal</a>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      let inputTotalElement = document.getElementById('total');
      let inputSubtotalElement = document.getElementById('subtotal');
      let inputTotal = 0;
      let inputSubtotal = 0;

      const selectedLayananItems = document.querySelectorAll('.selected-layanan-item');
      const addLayananItemButton = document.querySelectorAll('.add-item');

      function updateTotalHarga() {
      let total = 0;
      const selectedLayananItems = document.querySelectorAll('.selected-layanan-item');
      const challengeDescriptionContainer = document.getElementById('challenge-description-container');

      // Cek apakah ada challenge yang aktif
      const isChallengActive = challengeDescriptionContainer.innerHTML !== '';

      // Kumpulkan semua harga layanan
      selectedLayananItems.forEach((layananItem) => {
      // Gunakan data-layanan-previous-price untuk mendapatkan harga asli
      let layananPrice = parseFloat(layananItem.getAttribute('data-layanan-price'));

      if (!isNaN(layananPrice)) {
      total += layananPrice;
      }
      });

      console.log("Total harga sebelum perhitungan challenge: " + total);

      // Jika halaman dimuat dengan challenge yang sudah aktif
      // dan layanan gratis belum diatur ke 0
      if (isChallengActive) {
      const challengeDescription = document.getElementById('challenge-description');
      const challengeFreeLayananId = challengeDescription.getAttribute('data-challenge-free-layanan');

      // Periksa apakah harga layanan gratis sudah diatur ke 0
      let freeServiceAlreadyZero = false;
      selectedLayananItems.forEach((layananItem) => {
      const layananId = layananItem.getAttribute('data-layanan-id');
      if (layananId === challengeFreeLayananId) {
      const currentPrice = parseFloat(layananItem.getAttribute('data-layanan-price'));
      if (currentPrice === 0) {
      freeServiceAlreadyZero = true;
      }
      }
      });

      // Jika harga layanan gratis belum diatur ke 0, kurangi dari total
      if (!freeServiceAlreadyZero) {
      const challengeFreePrice = parseFloat(challengeDescription.getAttribute('data-challenge-free-layanan-price')) || 0;
      if (!isNaN(challengeFreePrice)) {
      console.log("Mengurangi harga layanan gratis: " + challengeFreePrice);
      total -= challengeFreePrice;
      }
      }
      }

      console.log("Total harga final: " + total);
      inputTotalElement.value = total;
      }


      function updateSubtotal() {
        const total = parseFloat(inputTotalElement.value) || 0; // Tambahkan || 0 untuk menghindari NaN
        let subtotal = 0;
        const badgeDescriptionContainer = document.getElementById('badge-description-container');
        const leaderboardDescriptionContainer = document.getElementById('leaderboard-description-container');
        const voucherDescriptionContainer = document.getElementById('voucher-description-container');

        let badgeDiscountResult = 0;
        let leaderboardDiscountResult = 0;
        let voucherDiscountResult = 0;

        if (badgeDescriptionContainer.innerHTML !== '') {
          const badgeDescription = document.getElementById('badge-description');
          const badgeDiscount = parseFloat(badgeDescription.getAttribute('data-badge-discount')) || 0;
          const badgeDescriptionValue = document.getElementById('badge-description-value');
          badgeDiscountResult = total * badgeDiscount;
          badgeDescriptionValue.textContent = "- Rp. " + badgeDiscountResult;
          console.log("Badge Discount: " + badgeDiscountResult);
        }

        if (leaderboardDescriptionContainer.innerHTML !== "") {
          const leaderboardDescription = document.getElementById('leaderboard-description');
          const leaderboardDiscount = parseFloat(leaderboardDescription.getAttribute('data-leaderboard-discount')) ||
            0;
          const leaderboardDescriptionValue = document.getElementById('leaderboard-description-value');
          leaderboardDiscountResult = total * leaderboardDiscount;
          leaderboardDescriptionValue.textContent = "- Rp. " + leaderboardDiscountResult;
          console.log("Leaderboard Discount: " + leaderboardDiscountResult);
        }

        if (voucherDescriptionContainer.innerHTML !== '') {
          const voucherDescription = document.getElementById('voucher-description');
          const voucherDiscount = parseFloat(voucherDescription.getAttribute('data-voucher-discount')) || 0;
          const voucherDescriptionValue = document.getElementById('voucher-description-value');
          voucherDiscountResult = total * voucherDiscount;
          voucherDescriptionValue.textContent = "- Rp. " + voucherDiscountResult;
          console.log("Voucher Discount: " + voucherDiscountResult);
        }

        subtotal = total - badgeDiscountResult - leaderboardDiscountResult - voucherDiscountResult;
        console.log("Subtotal: " + subtotal);

        inputSubtotalElement.value = subtotal;
      }

      // add layanan switch for non active layanan
      const layananSwitch = document.getElementById('layanan-active-switch');
      layananSwitch.addEventListener('change', function() {
        if (this.checked) {
          // Switch is ON
          console.log('Switch is turned on');
          // Add your logic here for when switch is turned on
          fetch('/dashboard/transaksi/active-switch', {
              method: 'POST',
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
              },
              body: JSON.stringify({
                isActive: this.checked,
              }),
            })
            .then((response) => response.json())
            .then((data) => {
              if (data && data.length > 0) {
                const layananContainer = document.getElementById('layananContainer');
                layananContainer.innerHTML = ``;

                data.forEach((layanan) => {
                  console.log(`Layanan Nama: ${layanan.nama_layanan}`);

                  const selectedLayananItems = document.querySelectorAll('.selected-layanan-item');
                  let selectedLayananId = '';
                  selectedLayananItems.forEach((layananItem) => {
                    selectedLayananId = layananItem.getAttribute('data-layanan-id');
                  });


                  layananContainer.innerHTML += `
                  <div class="col">
                    <div class="card shadow h-85" style="border-radius: 15px">
                      <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                          <h5 class="card-title p-0">${layanan.nama_layanan}</h5>
                          <p class="card-text">Rp ${Math.round(layanan.harga)}</p>
                        </div>
                        <div>
                          <button href="#" class="btn btn-primary add-item"
                            data-layanan-id="${layanan.id}" data-layanan-name="${layanan.nama_layanan}"
                            data-layanan-price="${layanan.harga}" ${selectedLayananId == layanan.id ? 'disabled' : ''}>
                            <i class="bi bi-plus-circle"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                `;
                });
              }
            })
            .catch(error => {
              console.error('There has been a problem with your fetch operation:', error);
            });
        } else {
          // Switch is OFF
          console.log('Switch is turned off');
          // Add your logic here for when switch is turned off
          fetch('/dashboard/transaksi/active-switch', {
              method: 'POST',
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
              },
              body: JSON.stringify({
                isActive: this.checked,
              }),
            })
            .then((response) => response.json())
            .then((data) => {
              if (data && data.length > 0) {
                const layananContainer = document.getElementById('layananContainer');
                layananContainer.innerHTML = ``;

                data.forEach((layanan) => {
                  console.log(`Layanan Nama: ${layanan.nama_layanan}`);

                  const selectedLayananItems = document.querySelectorAll('.selected-layanan-item');
                  let selectedLayananId = ``;
                  selectedLayananItems.forEach((layananItem) => {
                    selectedLayananId = layananItem.getAttribute('data-layanan-id');
                  });

                  layananContainer.innerHTML += `
                  <div class="col">
                    <div class="card shadow h-85" style="border-radius: 15px">
                      <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                          <h5 class="card-title p-0">${layanan.nama_layanan}</h5>
                          <p class="card-text">Rp ${Math.round(layanan.harga)}</p>
                        </div>
                        <div>
                          <button href="#" class="btn btn-primary add-item"
                            data-layanan-id="${layanan.id}" data-layanan-name="${layanan.nama_layanan}"
                            data-layanan-price="${layanan.harga}" ${selectedLayananId == layanan.id ? 'disabled' : ''}>
                            <i class="bi bi-plus-circle"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                `;
                });
              }
            })
            .catch(error => {
              console.error('There has been a problem with your fetch operation:', error);
            });
        }
      });

      // add new layanan item to selected layanan
      addLayananItemButton.forEach((button) => {
        button.addEventListener("click", function(event) {
          event.preventDefault();

          const selectedLayananItemsContainer = document.getElementById('selected-layanan-container');

          const newSelectedLayananItem = document.createElement('div');
          const layananId = button.getAttribute('data-layanan-id');
          const layananName = button.getAttribute('data-layanan-name');
          const layananPrice = button.getAttribute('data-layanan-price');
          newSelectedLayananItem.classList.add('col', 'selected-layanan-item');
          newSelectedLayananItem.setAttribute("data-layanan-id", layananId);
          newSelectedLayananItem.setAttribute("data-layanan-name", layananName);
          newSelectedLayananItem.setAttribute("data-layanan-price", layananPrice);

          newSelectedLayananItem.innerHTML = `
            <div class="card shadow h-85" style="border-radius: 15px">
              <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                  <h5 class="card-title p-0 m-0">
                    ${layananName}</h5>
                  <input type="hidden" name="layanan_id[]" value="${layananId}">
                </div>
                <div>
                  <button href="#" class="btn btn-primary remove-item"
                    data-layanan-id="${layananId}"
                    data-layanan-name="${layananName}"
                    data-layanan-price="${layananPrice}"
                    data-layanan-previous-price="${layananPrice}">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          `;

          selectedLayananItemsContainer.appendChild(newSelectedLayananItem);
          //tambahkan event listener untuk remove item yang baru
          const newRemoveItemButton = newSelectedLayananItem.querySelector('.remove-item');
          addRemoveItemEventListener(newRemoveItemButton);

          button.disabled = true;
          updateTotalHarga();
          //update voucher button is usable or not when no voucher used
          refreshVoucherButton();
          //update challenge button is usable or not when no challenge used
          refreshChallengeButton();
          updateSubtotal();
        })
      });

      //tambahkan semua event listener untuk remove item yang sudah ada
      const removeItemButtons = document.querySelectorAll('.remove-item');
      removeItemButtons.forEach((button) => {
        addRemoveItemEventListener(button);
      });

      // // remove selected layanan item function
      function addRemoveItemEventListener(button) {
        button.addEventListener("click", function(event) {
          event.preventDefault();

          const layananItem = button.closest('.selected-layanan-item');
          const removeLayananId = layananItem.getAttribute('data-layanan-id');
          layananItem.remove();

          const layananAddItemButtons = document.querySelectorAll('.add-item');
          layananAddItemButtons.forEach((addItembutton) => {
            let layananId = addItembutton.getAttribute('data-layanan-id');
            if (layananId == removeLayananId) {
              addItembutton.disabled = false;
            }
          });

          const challengeDescriptionContainer = document.getElementById('challenge-description-container');
          if (challengeDescriptionContainer.innerHTML !== "") {
            const challengeFreeLayananId = document.getElementById('challenge-description').getAttribute(
              'data-challenge-free-layanan');

            if (challengeFreeLayananId == removeLayananId) {
              challengeDescriptionContainer.innerHTML = "";
            }
          }

          updateTotalHarga();
          refreshVoucherButton();
          refreshChallengeButton();
          updateSubtotal();
        });
      }

      // voucher check initiation when first time load page
      function checkVoucherUsed() {
        const challengeDescriptionContainer = document.getElementById('challenge-description-container');
        const voucherDescriptionContainer = document.getElementById('voucher-description-container');

        if (voucherDescriptionContainer || challengeDescriptionContainer) {
          const voucherAddItemButtons = document.querySelectorAll('.voucher-add-item');

          voucherAddItemButtons.forEach((button) => {
            button.disabled = true;
          });
        } else {
          refreshVoucherButton();
        }
      }

      function refreshVoucherButton() {
        const voucherDescriptionContainer = document.getElementById('voucher-description-container');
        const challengeDescriptionContainer = document.getElementById('challenge-description-container');
        const voucherListContainer = document.getElementById('voucher-list-container');

        if (voucherDescriptionContainer.innerHTML === "" || challengeDescriptionContainer.innerHTML === "") {
          if (voucherListContainer) {
            const total = parseFloat(inputTotalElement.value);
            const voucherAddItemButtons = document.querySelectorAll('.voucher-add-item');

            voucherAddItemButtons.forEach((button) => {
              let minimumTransaction = parseFloat(button.getAttribute(
                'data-voucher-minimum-transaction'));
              console.log("Minimum Transaction: " + minimumTransaction);

              if (minimumTransaction <= total) {
                button.disabled = false;
              } else {
                button.disabled = true;
              }
            });
          }
        }
      }

      // add voucher click event
      function clickVoucherItem() {
        const voucherAddItemButtons = document.querySelectorAll('.voucher-add-item');

        voucherAddItemButtons.forEach((button) => {
          button.addEventListener('click', function(event) {
            event.preventDefault();
            const voucherDescriptionContainer = document.getElementById(
              'voucher-description-container');
            const voucherId = button.getAttribute('data-voucher-id');
            const voucherName = button.getAttribute('data-voucher-name');
            const voucherDiscount = parseFloat(button.getAttribute('data-voucher-discount'));
            const voucherMinimumTransaction = button.getAttribute('data-voucher-minimum-transaction');
            const total = parseFloat(document.getElementById('total').value);

            const newVoucherDescription = document.createElement('div');
            newVoucherDescription.classList.add('d-flex', 'justify-content-between',
              'align-items-center',
              'mb-2');
            newVoucherDescription.setAttribute('id', 'voucher-description');
            newVoucherDescription.setAttribute('data-voucher-id', voucherId);
            newVoucherDescription.setAttribute('data-voucher-discount', voucherDiscount);
            newVoucherDescription.setAttribute('data-voucher-minimum-transaction',
              voucherMinimumTransaction);

            newVoucherDescription.innerHTML = `
              <input type="hidden" name="voucher_id" value="${voucherId}">  
              <p class="m-0">${voucherName}</p>
              <div class="d-flex justify-content-end align-items-center">
                <p class="m-0" id="voucher-description-value">- Rp. ${total * voucherDiscount}</p>
                <button type="button" class="btn p-0 ms-2 my-auto" id="remove-voucher">
                  <i class="bi bi-x-circle"></i>
                </button>
              </div>
            `;

            voucherDescriptionContainer.appendChild(newVoucherDescription);

            // disabling all voucher add item button
            const voucherAddItemButtons = document.querySelectorAll('.voucher-add-item');
            voucherAddItemButtons.forEach((voucherItemButton) => {
              voucherItemButton.disabled = true;
            });

            // disabling all challenge add item button
            const challengeAddItemButtons = document.querySelectorAll('.challenge-add-item');
            challengeAddItemButtons.forEach((challengeItemButton) => {
              challengeItemButton.disabled = true;
            });

            updateSubtotal();

            const removeVoucherButton = document.getElementById('remove-voucher');
            removeVoucherButton.addEventListener('click', removeUsedVoucher);
          });
        });
      }

      // add remove used voucher button in voucher description
      const voucherDescriptionContainer = document.getElementById('voucher-description-container');
      if (voucherDescriptionContainer.innerHTML !== "") {
        const removeVoucherButton = document.getElementById('remove-voucher');

        removeVoucherButton.addEventListener('click', removeUsedVoucher);
      }

      function removeUsedVoucher(event) {
        event.preventDefault();
        console.log("Event click berjalan")

        voucherDescriptionContainer.innerHTML = "";

        refreshVoucherButton();
        refreshChallengeButton();
        updateSubtotal();
      }

      // add remove used challenge button in challenge description
      const challengeDescriptionContainer = document.getElementById('challenge-description-container');
      if (challengeDescriptionContainer.innerHTML !== '') {
        console.log("Challenge Description Container is not empty");
        const removeChallengeButton = document.getElementById('remove-challenge');

        removeChallengeButton.addEventListener('click', removeUsedChallenge);
      } else {
        console.log("Challenge Description Container is empty");
      }

      function removeUsedChallenge(event) {
        // event.preventDefault();
        console.log("Event click berjalan");

        const challengeDescriptionContainer = document.getElementById('challenge-description-container');
        const challengeAddItemButtons = document.querySelectorAll('.challenge-add-item');
        const voucherAddItemButtons = document.querySelectorAll('.voucher-add-item');
        const total = parseFloat(document.getElementById('total').value);

        // check if selected layanans are free layanans
        const challengeList = document.querySelectorAll('.challenge-list');

        challengeList.forEach((challenge) => {
          let challengeFreeLayanan = challenge.getAttribute('data-challenge-free-layanan');
          const selectedLayanan = document.querySelectorAll('.selected-layanan-item');

          selectedLayanan.forEach((layanan) => {
            let selectedLayananName = layanan.getAttribute('data-layanan-name');
            let selectedLayananId = layanan.getAttribute('data-layanan-id');
            let selectedLayananPrice = parseFloat(layanan.getAttribute('data-layanan-price'));
            let selectedLayananPreviousPrice = parseFloat(layanan.getAttribute(
              'data-layanan-previous-price'));

            // change h5 element text content
            let h5Element = layanan.querySelector('h5');
            if (h5Element) {
              h5Element.textContent = `${selectedLayananName}`;
            }

            // change free price to normal price
            layanan.setAttribute('data-layanan-price', selectedLayananPreviousPrice);
            const removeItemButton = layanan.querySelector('.remove-item');
            removeItemButton.setAttribute('data-layanan-price', selectedLayananPreviousPrice);
          });
        });

        challengeDescriptionContainer.innerHTML = "";

        updateTotalHarga();
        refreshVoucherButton();
        refreshChallengeButton();
        updateSubtotal();
      }

      function refreshChallengeButton() {
        const challengeDescriptionContainer = document.getElementById('challenge-description-container');
        const voucherDescriptionContainer = document.getElementById('voucher-description-container');

        if (challengeDescriptionContainer.innerHTML === "" || voucherDescriptionContainer.innerHTML === "") {
          const selectedLayananItems = document.querySelectorAll('.selected-layanan-item');
          const challengeListContainer = document.getElementById('challenge-list-container');

          if (challengeListContainer.innerHTML !== "") {
            const challengeAddItemButtons = document.querySelectorAll('.challenge-add-item');

            challengeAddItemButtons.forEach((challengeItemButton) => {
              challengeItemButton.disabled = true;
              const challengeFreeLayanan = parseInt(challengeItemButton.getAttribute(
                'data-challenge-free-layanan'));

              selectedLayananItems.forEach((selectedItem) => {
                const selectedLayananId = parseInt(selectedItem.getAttribute('data-layanan-id'));

                if (selectedLayananId === challengeFreeLayanan) {
                  setTimeout(() => {
                    challengeItemButton.disabled = false;
                  }, 0);
                }
              });
            });
          }
        }
      }

      function clickChallengeItem() {
        const challengeAddItemButtons = document.querySelectorAll('.challenge-add-item');

        challengeAddItemButtons.forEach((button) => {
          button.addEventListener('click', function(event) {
            event.preventDefault();

            const challengeDescriptionContainer = document.getElementById('challenge-description-container');
            const selectedLayananItems = document.querySelectorAll('.selected-layanan-item');
            const challengeId = button.getAttribute('data-challenge-id');
            const challengeDescription = button.getAttribute('data-challenge-description');
            const challengeFreeLayanan = button.getAttribute('data-challenge-free-layanan');
            const challengeFreeLayananName = button.getAttribute('data-challenge-free-layanan-name');

            // Cari harga layanan yang akan digratiskan
            let challengeFreeLayananPrice = 0;
            selectedLayananItems.forEach((layanan) => {
              let selectedLayananId = layanan.getAttribute('data-layanan-id');
              if (selectedLayananId == challengeFreeLayanan) {
                challengeFreeLayananPrice = parseFloat(layanan.getAttribute(
                  'data-layanan-previous-price'));
              }
            });

            const newChallengeDescription = document.createElement('div');
            newChallengeDescription.classList.add('d-flex', 'justify-content-between',
              'align-items-center', 'mb-2');
            newChallengeDescription.setAttribute('id', 'challenge-description');
            newChallengeDescription.setAttribute('data-challenge-id', challengeId);
            newChallengeDescription.setAttribute('data-challenge-free-layanan', challengeFreeLayanan);
            newChallengeDescription.setAttribute('data-challenge-free-layanan-name',
            challengeFreeLayananName);
            newChallengeDescription.setAttribute('data-challenge-free-layanan-price',
              challengeFreeLayananPrice);

            newChallengeDescription.innerHTML = `
      <p class="m-0">${challengeDescription}</p>
      <div class="d-flex justify-content-end align-items-center">
        <input type="hidden" name="challenge_progress_id" value="${challengeId}">
        <p class="m-0">Gratis ${challengeFreeLayananName}</p>
        <button type="button" class="btn p-0 ms-2 my-auto" id="remove-challenge">
          <i class="bi bi-x-circle"></i>
        </button>
      </div>
      `;

            selectedLayananItems.forEach((layanan) => {
              let selectedLayananId = layanan.getAttribute('data-layanan-id');
              let selectedLayananName = layanan.getAttribute('data-layanan-name');
              let selectedLayananPrice = parseFloat(layanan.getAttribute('data-layanan-price'));
              let selectedLayananPreviousPrice = parseFloat(layanan.getAttribute(
                'data-layanan-previous-price'));

              if (selectedLayananId == challengeFreeLayanan) {
                layanan.setAttribute('data-layanan-price', 0);
                const removeItemButton = layanan.querySelector('.remove-item');
                removeItemButton.setAttribute('data-layanan-price', 0);

                // change h5 element text content
                let h5Element = layanan.querySelector('h5');
                if (h5Element) {
                  h5Element.textContent = `${selectedLayananName} (Gratis)`;
                }
              }
            });

            button.disabled = true;
            challengeDescriptionContainer.appendChild(newChallengeDescription);

            // disabling all voucher add item button
            const voucherAddItemButtons = document.querySelectorAll('.voucher-add-item');
            voucherAddItemButtons.forEach((voucherItemButton) => {
              voucherItemButton.disabled = true;
            });

            // disabling all other challenge add item buttons
            challengeAddItemButtons.forEach((challengeButton) => {
              if (challengeButton !== button) {
                challengeButton.disabled = true;
              }
            });

            updateTotalHarga();
            updateSubtotal();

            const removeChallengeButton = document.getElementById('remove-challenge');
            removeChallengeButton.addEventListener('click', removeUsedChallenge);
          });
        });
      }


      function checkChallengeUsed() {
        const challengeDescriptionContainer = document.getElementById('challenge-description-container');
        const voucherDescriptionContainer = document.getElementById('voucher-description-container');

        if (challengeDescriptionContainer || voucherDescriptionContainer) {
          const challengeAddItemButtons = document.querySelectorAll('.challenge-add-item');

          challengeAddItemButtons.forEach((button) => {
            button.disabled = true;
          })
        }
      }

      // Initial call to set the total price if there are pre-selected options
      updateTotalHarga();
      checkVoucherUsed();
      checkChallengeUsed();
      clickVoucherItem();
      clickChallengeItem();
      updateSubtotal();
    });
</script>
@endsection