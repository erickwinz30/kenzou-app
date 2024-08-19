@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Layanan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Layanan</li>
        <li class="breadcrumb-item">Tambah</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Edit Layanan</h5>
            <form action="/dashboard/layanan/{{ $layanan->id }}" method="POST">
              @method('put')
              @csrf
              <div class="mb-3">
                <label for="nama_layanan" class="form-label @error('nama_layanan') is-invalid @enderror">Layanan</label>
                <input type="text" class="form-control" id="nama_layanan" name="nama_layanan"
                  value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required autofocus>
                @error('nama_layanan')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="harga" class="form-label @error('harga') is-invalid @enderror">Harga</label>
                <input type="decimal" class="form-control" id="harga" name="harga"
                  value="{{ old('harga', $layanan->harga) }}" required autofocus>
                @error('harga')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="detail" class="form-label @error('detail') is-invalid @enderror">Layanan</label>
                <textarea class="form-control" id="detail" name="detail" required autofocus>{{ old('detail', $layanan->detail) }}</textarea>
                @error('detail')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="modal-footer">
                <a href="/dashboard/layanan" class="btn btn-secondary me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
