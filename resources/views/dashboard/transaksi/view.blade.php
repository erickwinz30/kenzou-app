@extends('dashboard.layout.main')

@section('container')
  <style>
    .rounded-thead {
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      overflow: hidden;
      /* This ensures that the corners appear correctly */
    }

    .rounded-thead th:first-child {
      border-top-left-radius: 10px;
      border-bottom-left-radius: 10px;
    }

    .rounded-thead th:last-child {
      border-top-right-radius: 10px;
      border-bottom-right-radius: 10px;
    }
  </style>
  <div class="pagetitle">
    <h1>Transaksi</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard/admin">Admin</a></li>
        <li class="breadcrumb-item"><a href="/dashboard/transaksi">Transaksi</a></li>
        <li class="breadcrumb-item"><a href="/dashboard/transaksi/{{ $transaksi->id }}">Detail Transaksi</a></li>
      </ol>
    </nav>

  </div><!-- End Page Title -->

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <h1 class="card-title fs-5 py-1">ID: {{ $transaksi->id }}</h1>
        <div class="d-flex mt-3 align-items-center mb-2">
          <h1 class="fs-6 fw-bold p-0 m-0 me-2" style="color: #012970;">Rp
            {{ number_format($transaksi->total_harga, 0, ',', '.') }}</h1>
          <p class="m-0 me-2">/</p>
          @if ($transaksi->metode_pembayaran == 'tunai')
            <h1 class="card-text fw-semibold p-0 fs-6" style="color: #012970;">Tunai</h1>
          @elseif($transaksi->metode_pembayaran == 'qris')
            <h1 class="card-text fw-semibold p-0" style="color: #012970;">QRis</h1>
          @endif
        </div>
        <div class="row justify-content-center">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-6 mt-4">
                    <div class="d-flex justify-content-between">
                      <p><strong>No. Pelanggan: </strong></p>
                      <p>{{ $transaksi->nomor_telepon }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                      <p><strong>Keterangan: </strong></p>
                      <p>{{ $transaksi->keterangan }}</p>
                    </div>
                  </div>
                  <div class="col-12 col-md-6 mt-4">
                    <div class="d-flex justify-content-between">
                      <p><strong>Transaksi ID: </strong></p>
                      <p class="text-end">{{ $transaksi->id }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                      <p><strong>Tgl Transaksi:</strong></p>
                      <p>{{ $transaksi->date }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                      <p><strong>Kasir:</strong></p>
                      <p>{{ $transaksi->user->nama }}</p>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row px-3">
                  <table class="table table-borderless col-12">
                    <thead class="table-secondary rounded-thead">
                      <tr>
                        <th>Layanan</th>
                        <th class="text-end">Harga</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($detailLayanans as $detailLayanan)
                        <tr>
                          <td>{{ $detailLayanan->layanan->nama_layanan }}</td>
                          <td class="text-end">Rp {{ number_format($detailLayanan->layanan->harga, 0, ',', '.') }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <hr>
                <div class="row">
                  <div class="col-12 col-md-6">
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-between">
                      <p><strong>Subtotal: </strong></p>
                      <p class="text-end">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
@endsection
