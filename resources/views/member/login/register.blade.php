@extends('member.login.layout.main')

@section('container')
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

            @if (session()->has('error'))
              <div class="row justify-content-center">
                <div class="alert alert-danger alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
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
                  <h5 class="card-title text-center pb-0 fs-4">Buat Akun Member</h5>
                  <p class="text-center small">Masukkan data diri anda untuk membuat akun</p>
                </div>

                <form action="/register" method="POST" class="row g-3 needs-validation" novalidate>
                  @csrf
                  <div class="col-12">
                    <label for="nama" class="form-label">Nama</label>
                    <div class="input-group has-validation">
                      <input type="text" name="nama" class="form-control" id="nama"
                        placeholder="Masukkan nama..." @error('nama') is-invalid @enderror required
                        value="{{ old('nama') }}">
                      @error('nama')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group has-validation">
                      <input type="email" name="email" class="form-control" id="email"
                        placeholder="Masukkan email..." @error('email') is-invalid @enderror required
                        value="{{ old('email') }}">
                      @error('email')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="nomor_telepon" class="form-label">No. Telepon</label>
                    <div class="input-group has-validation">
                      <input type="text" inputmode="numeric" name="nomor_telepon" class="form-control"
                        id="nomor_telepon" placeholder="Masukkan no. telepon..."
                        @error('nomor_telepon') is-invalid @enderror required value="{{ old('nomor_telepon') }}">
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
                        placeholder="Masukkan tanggal_lahir..." @error('tanggal_lahir') is-invalid @enderror required
                        value="{{ old('tanggal_lahir') }}">
                      @error('tanggal_lahir')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group has-validation d-flex">
                      <input type="password" name="password" class="form-control" id="password" required
                        placeholder="Masukkan password..." @error('password') is-invalid @enderror>
                      <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                        style="margin-left: 6px; border-radius: 0.375rem; padding: 0.375rem 0.75rem;">
                        <i class="bi bi-eye"></i>
                      </button>
                      @error('password')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="referral_code" class="form-label">Referral Code</label>
                    <div class="input-group has-validation">
                      <input type="text" name="referral_code" class="form-control" id="referral_code"
                        placeholder="Masukkan referral code (optional)..." @error('referral_code') is-invalid @enderror
                        value="{{ old('referral_code') }}">
                      @error('referral_code')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Buat Account</button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0">Sudah memiliki akun? <a href="{{ route('login') }}">Log in</a></p>
                  </div>
                </form>

                <p class="small text-center mt-3">Atau</p>

                <div>
                  <a href="/auth/google" class="btn btn-light w-100" style="border: 1px solid black"><i
                      class="bi bi-google me-3"></i>Sign-in dengan
                    Google</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>

  <script>
    //tombol tampilkan password
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const icon = this.querySelector('i');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
      }
    });
  </script>
@endsection
