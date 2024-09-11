@extends('member.login.layout.main')

@section('container')
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            @if (session()->has('success'))
              <div class="row justify-content-center">
                <div class="alert alert-success alert-dismissible fade show col-lg-12 justify-content-center"
                  role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              </div>
            @endif

            @if (session()->has('error'))
              <div class="row justify-content-center">
                <div class="alert alert-danger alert-dismissible fade show col-lg-12 justify-content-center"
                  role="alert">
                  {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              </div>
            @endif

            <div class="d-flex justify-content-center py-4">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="{{ asset('img/icons/icon-152.png') }}" alt="" style="max-height: 6em">
                <span class="d-none d-lg-block text-center">Kenzou Drive Thru Car Wash</span>
              </a>
            </div><!-- End Logo -->

            @if (session()->has('loginError'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('loginError') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <div class="card mb-3">

              <div class="card-body">

                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-6">Untuk mengakses akun Anda, berikan nomor telepon, tanggal
                    lahir, dan kode referral jika ada.</h5>
                  {{-- <p class="text-center small">Untuk mengakses akun Anda, berikan nomor telepon, tanggal lahir, dan kode
                    referral Anda.</p> --}}
                </div>

                <form action='/register/next' method="POST" class="row g-3 needs-validation" novalidate>
                  @csrf
                  <div class="col-12">
                    <label for="nomor_telepon" class="form-label">No. Telepon</label>
                    <div class="input-group has-validation">
                      <input type="text" inputmode="numeric" name="nomor_telepon" class="form-control"
                        id="nomor_telepon" placeholder="Masukkan no. telepon" @error('nomor_telepon') is-invalid @enderror
                        required>
                      {{-- <div class="invalid-feedback">Please enter your nomor_telepon.</div> --}}
                      @error('nomor_telepon')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="tanggal_lahir" class="form-label">Tgl Lahir</label>
                    <div class="input-group has-validation">
                      <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir"
                        placeholder="Masukkan tanggal lahir..." @error('tanggal_lahir') is-invalid @enderror required>
                      @error('tanggal_lahir')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="referral_code" class="form-label">Referral Code</label>
                    <input type="text" name="referral_code" class="form-control" id="referral_code"
                      placeholder="Masukkan referral code jika ada" @error('referral_code') is-invalid @enderror>
                    @error('referral_code')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

  </div>
@endsection
