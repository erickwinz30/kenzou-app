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
                  <h5 class="card-title text-center pb-0 fs-4">Login ke Akun Member</h5>
                  <p class="text-center small">Masukkan email & password untuk login</p>
                </div>

                <form action='/login' method="POST" class="row g-3 needs-validation" novalidate>
                  @csrf
                  <div class="col-12">
                    <label for="nomor_telepon" class="form-label">No. Telp</label>
                    <div class="input-group has-validation">
                      <input type="text" inputmode="numeric" name="nomor_telepon" class="form-control"
                        id="nomor_telepon" placeholder="Masukkan no telepon..."
                        @error('nomor_telepon') is-invalid @enderror required>
                      {{-- <div class="invalid-feedback">Please enter your nomor telepon.</div> --}}
                      @error('nomor_telepon')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password"
                      placeholder="Masukkan password..." required>
                    <div class="invalid-feedback">Please enter your password!</div>
                  </div>
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                  </div>

                  <div class="col-12">
                    <p class="small mb-0">Belum memiliki akun? <a href="/register">Buat akun</a></p>
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
    document.getElementById('nomor_telepon').addEventListener('input', function(e) {
      this.value = this.value.replace(/[^0-9]/g, '');
    });
  </script>
@endsection
