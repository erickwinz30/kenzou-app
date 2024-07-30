@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Kasir</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Kasir</li>
        <li class="breadcrumb-item"><a href="/list-layanan">List Layanan</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard mt-4">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 align-items-top">
      @foreach ($layanans as $layanan)
        <div class="col mb-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">{{ $layanan->nama_layanan }}</h5>
              {{ $layanan->detail }}
              <p class="card-text"></p>
            </div>
            <div class="card-footer">
              <b>Harga:</b> Rp {{ number_format($layanan->harga, 0, ',', '.') }}
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </section>
@endsection
