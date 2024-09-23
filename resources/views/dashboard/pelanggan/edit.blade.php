@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Pelanggan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Pelanggan</li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <div class="row justify-content-center">
      <div class="alert alert-success alert-dismissible fade show col-lg-10" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  @if (session()->has('error'))
    <div class="row justify-content-center">
      <div class="alert alert-danger alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Edit Pelanggan</h5>
            <form action="/dashboard/pelanggan/{{ $pelanggan->id }}" method="POST">
              @method('put')
              @csrf
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-2 d-flex">
                  <label for="id_pelanggan" class="form-label me-3 @error('id_pelanggan') is-invalid @enderror">ID
                    Pelanggan: </label>
                  <p>{{ Str::limit($pelanggan->id, 25) }}</p>
                </div>
                <div class="mb-2 d-flex">
                  <label for="referral_code" class="form-label me-3 @error('referral_code') is-invalid @enderror">Referral
                    Code: </label>
                  <p>{{ $pelanggan->member->referral_code }}</p>
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mb-3">
                <div>
                  <label for="nomor_telepon" class="form-label @error('nomor_telepon') is-invalid @enderror">No.
                    Telepon</label>
                  <input type="text" inputmode="numeric" class="form-control" id="nomor_telepon" name="nomor_telepon"
                    value="{{ old('nomor_telepon', $pelanggan->nomor_telepon) }}" required autofocus>
                  @error('nomor_telepon')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div>
                  <label for="nama" class="form-label @error('nama') is-invalid @enderror">Nama</label>
                  <input type="text" inputmode="numeric" class="form-control" id="nama" name="nama"
                    value="{{ old('nama', $pelanggan->member->nama) }}" required autofocus>
                  @error('nama')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mb-3">
                @if ($pelanggan->member->google_id)
                  <div class="d-block">
                    <label for="email" class="form-label me-3 @error('email') is-invalid @enderror">Email</label>
                    <p>{{ $pelanggan->member->email }}</p>
                  </div>
                @else
                  <div>
                    <label for="email" class="form-label @error('email') is-invalid @enderror">Email</label>
                    <input type="text" class="form-control" id="email" name="email"
                      value="{{ old('email', $pelanggan->member->email) }}" required autofocus>
                    @error('email')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                @endif
                <div>
                  <label for="tanggal_lahir" class="form-label @error('tanggal_lahir') is-invalid @enderror">Tgl
                    Lahir</label>
                  <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                    value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($pelanggan->member->tanggal_lahir)->format('Y-m-d')) }}"
                    required autofocus>
                  @error('tanggal_lahir')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mb-3">
                <div>
                  <label for="experience_point" class="form-label @error('experience_point') is-invalid @enderror">Exp
                    Point</label>
                  <input type="text" inputmode="numeric" class="form-control" id="experience_point"
                    name="experience_point" value="{{ old('experience_point', $pelanggan->member->experience_point) }}"
                    required autofocus>
                  @error('experience_point')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div>
                  <label for="redeemable_point" class="form-label @error('redeemable_point') is-invalid @enderror">Redeem
                    Point</label>
                  <input type="text" inputmode="numeric" class="form-control" id="redeemable_point"
                    name="redeemable_point" value="{{ old('redeemable_point', $pelanggan->member->redeemable_point) }}"
                    required autofocus>
                  @error('redeemable_point')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="modal-footer">
                <a href="/dashboard/pelanggan" class="btn btn-secondary me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
