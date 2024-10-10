@extends('member.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Edit Profile</h1>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <div class="row justify-content-center">
      <div class="col-md-10 col-lg-8">
        <div class="alert alert-success alert-dismissible fade show col-lg-10" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    </div>
  @endif

  @if (session()->has('error'))
    <div class="row justify-content-center">
      <div class="col-md-10 col-lg-8">
        <div class="alert alert-danger alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    </div>
  @endif

  <section class="section mb-5">
    <div class="row justify-content-center">
      <div class="col-md-10 col-lg-8">
        <div class="card">
          <div class="card-body">
            <form action="/account/edit/post" method="POST" id="deleteForm">
              @csrf
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mb-3 mt-4">
                <div class="mb-3">
                  <label for="nama" class="form-label fw-bold @error('nama') is-invalid @enderror">Nama:</label>
                  <input type="text" inputmode="numeric" class="form-control" id="nama" name="nama"
                    value="{{ old('nama', $member->nama) }}" required autofocus>
                  @error('nama')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div>
                  @if ($member->google_id)
                    <div class="d-block">
                      <label for="email"
                        class="form-label me-3 fw-bold @error('email') is-invalid @enderror">Email:</label>
                      <p class="m-0">{{ $member->email }} (Google)</p>
                    </div>
                  @else
                    <div class="d-block">
                      <label for="email" class="form-label fw-bold @error('email') is-invalid @enderror">Email:</label>
                      <input type="text" class="form-control" id="email" name="email"
                        value="{{ old('email', $member->email) }}" required autofocus>
                      @error('email')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  @endif
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-3">
                  <label for="nomor_telepon" class="form-label fw-bold @error('nomor_telepon') is-invalid @enderror">No.
                    Telepon:</label>
                  <input type="text" inputmode="numeric" class="form-control" id="nomor_telepon" name="nomor_telepon"
                    value="{{ old('nomor_telepon', $member->nomor_telepon) }}" required autofocus>
                  @error('nomor_telepon')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div>
                  <label for="tanggal_lahir" class="form-label fw-bold @error('tanggal_lahir') is-invalid @enderror">Tgl
                    Lahir:</label>
                  <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                    value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($member->tanggal_lahir)->format('Y-m-d')) }}"
                    required autofocus>
                  @error('tanggal_lahir')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              @if ($member->google_id)
                <div class="row mt-3">
                  <div class="mb-2 d-flex">
                    <label for="referral_code"
                      class="form-label me-3 @error('referral_code') is-invalid @enderror"><strong>Referral
                        Code: </strong></label>
                    <p>{{ $member->referral_code }}</p>
                  </div>
                </div>
              @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mt-3">
                  <div class="mb-2">
                    <label for="password"
                      class="form-label fw-bold @error('password') is-invalid @enderror">Password:</label>
                    <div class="input-group has-validation d-flex">
                      <input type="password" class="form-control" id="password" name="password" autofocus>
                      <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                        style="margin-left: 6px; border-radius: 0.375rem; padding: 0.375rem 0.75rem;">
                        <i class="bi bi-eye"></i>
                      </button>
                    </div>
                    @error('password')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="mb-2">
                    <label for="referral_code"
                      class="form-label me-3 @error('referral_code') is-invalid @enderror"><strong>Referral
                        Code: </strong></label>
                    <p>{{ $member->referral_code }}</p>
                  </div>
                </div>
              @endif
              <div class="modal-footer">
                <a href="/account" class="btn btn-secondary me-1">Batal</a>
                <button type="button" class="btn btn-primary" onclick="deleteConfirmation()">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    //tombol tampilkan password
    document.getElementById('togglePassword').addEventListener('click',
      function() {
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

    //konfirmasi hapus data
    function deleteConfirmation() {
      Swal.fire({
        title: "Yakin ingin mengubah data diri?",
        text: "Aksi ini tidak bisa mengembalikan data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('deleteForm').submit();
        }
      });
    };
  </script>
@endsection
