@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Voucher</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Voucher</li>
        <li class="breadcrumb-item active">Tambah</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('error'))
    <x-alert-error :message="session('error')" />
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Tambah Voucher</h5>
            <form action="/dashboard/voucher" method="POST" id="addForm" enctype="multipart/form-data">
              @csrf
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-3">
                  <label for="nama" class="form-label @error('nama') is-invalid @enderror">Nama / Deskripsi
                    Singkat</label>
                  <input type="text" class="form-control" id="nama" name="nama" required autofocus>
                  @error('nama')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="description" class="form-label @error('description') is-invalid @enderror">Deskripsi</label>
                  <input type="text" class="form-control" id="description" name="description" required autofocus>
                  @error('description')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div>
                  <label for="point_needed" class="form-label @error('point_needed') is-invalid @enderror">Point yang
                    Diperlukan</label>
                  <input type="text" inputmode="numeric" class="form-control" id="point_needed" name="point_needed"
                    required autofocus>
                  @error('point_needed')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="discount" class="form-label @error('discount') is-invalid @enderror">Diskon</label>
                  <div class="input-group">
                    <input type="text" inputmode="numeric" class="form-control" id="discount" name="discount" required
                      autofocus>
                    <span class="input-group-text" id="inputGroupPrepend">%</span>
                  </div>
                  @error('discount')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Tanggal Berlaku:</label>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mb-3">
                  <div class="mb-3">
                    <label for="from_date" class="form-label">Dari</label>
                    <input class="form-control @error('from_date') is-invalid @enderror" type="datetime-local"
                      id="from_date" name="from_date" onchange="previewImage()">
                    @error('from_date')
                      <div class="invalid-feedback">
                        <p>{{ $message }}</p>
                      </div>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="to_date" class="form-label">Sampai</label>
                    <input class="form-control @error('to_date') is-invalid @enderror" type="datetime-local"
                      id="to_date" name="to_date" onchange="previewImage()">
                    @error('to_date')
                      <div class="invalid-feedback">
                        <p>{{ $message }}</p>
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <a href="/dashboard/voucher" class="btn btn-secondary me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Tambah</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
