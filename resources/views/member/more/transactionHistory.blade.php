@extends('member.layout.main')

@section('container')
  <style>
    .back-to-top {
      bottom: 80px;
    }
  </style>

  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Riwayat Transaksi</h1>
    <a href="{{ route('account') }}" class="btn btn-primary"
      style="background-color: #012970; border-color:#012970">Kembali</a>
  </div><!-- End Page Title -->

  <section class="section mt-4 mb-5">
    <div class="col-12">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        @foreach ($transactions as $transaction)
          <div class="col">
            <div class="card shadow" style="border-radius: 15px">
              <div class="card-title p-3 m-0">ID: {{ Str::limit($transaction->id, 15) }}</div>
              <div class="d-flex justify-content-between align-items-center p-3">
                <div class="card-text"><strong style="color: #012970">Tgl & Waktu: </strong> {{ $transaction->date }}
                </div>
                <div class="card-text"><strong style="color: #012970">Total harga: </strong>
                  Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection
