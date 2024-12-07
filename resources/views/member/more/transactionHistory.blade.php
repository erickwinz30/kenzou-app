@extends('member.layout.main')

@section('container')
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Riwayat Transaksi</h1>
    <a href="{{ route('account') }}" class="btn btn-primary"
      style="background-color: #012970; border-color:#012970">Kembali</a>
  </div><!-- End Page Title -->

  <section class="section mt-4 mb-5">
    <div class="col-12">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        @foreach ($transactions as $transaction)
          <a href="/account/view-transaction-history/{{ $transaction->id }}">
            <div class="col">
              <div class="card shadow" style="border-radius: 15px">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="card-title p-3 pb-0 m-0">ID: {{ Str::limit($transaction->id, 12) }}</div>
                  <div class="card-text p-3 pb-0 m-0 fs-7">
                    @php
                      $formattedDateTime = \Carbon\Carbon::parse($transaction->date)->translatedFormat('j F Y, H:i');
                    @endphp
                    {{ $formattedDateTime }}
                  </div>
                </div>
                <div class="layanan-item d-flex p-3 pb-0">
                  @php
                    $layananNames = $transaction->detail_layanan
                        ->map(function ($detailLayanan) {
                            return $detailLayanan->layanan->nama_layanan;
                        })
                        ->toArray();
                  @endphp
                  <p class="p-0 mb-1">{{ implode(', ', $layananNames) }}</p>
                </div>
                <div class="card-text p-3">
                  <strong style="color: #012970">Total harga: </strong>
                  Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                </div>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>
@endsection
