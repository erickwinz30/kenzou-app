@extends('dashboard.layout.main')

@section('container')
<div class="pagetitle">
  <h1>Layanan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Admin</li>
      <li class="breadcrumb-item active"><a href="/dashboard/layanan">Layanan</a></li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3 fs-4">Detail Layanan</h5>
          <div class="mb-4">
            <h5 class="mb-0">Nama Layanan</h5>
            <p>{{ $layanan->nama_layanan }}</p>
          </div>
          <div class="mb-4">
            <h5 class="mb-0">Detail Layanan</h5>
            <p>{{ $layanan->detail }}</p>
          </div>
          <div class="mb-4">
            <h5 class="mb-0">Harga Layanan</h5>
            <p>Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
          </div>
          <div class="mb-4">
            <h5 class="mb-0">Point yang akan diperoleh member</h5>
            <p>{{ $layanan->point }}</p>
          </div>
          <div class="mb-4">
            <h5 class="mb-0">Kategori Layanan</h5>
            <p>{{ $layanan->categoryLayanan->name }}</p>
          </div>
          <a href="/dashboard/layanan" class="btn btn-info">Kembali</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection