@extends('dashboard.layout.main')

@section('container')
<div class="pagetitle">
  <h1>Badge</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Admin</li>
      <li class="breadcrumb-item active">Badge</li>
      <li class="breadcrumb-item active">Tambah</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

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
          <h5 class="card-title">Tambah Badge Leaderboard</h5>
          <form action="/dashboard/badge-leaderboard" method="POST" id="addForm" enctype="multipart/form-data">
            @csrf
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div class="mb-3">
                <label for="badge_name" class="form-label @error('badge_name') is-invalid @enderror">Nama Badge</label>
                <input type="text" class="form-control" id="badge_name" name="badge_name"
                  placeholder="Masukkan nama badge..." required autofocus>
                @error('badge_name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="rank" class="form-label @error('rank') is-invalid @enderror">Untuk Peringkat</label>
                <select class="form-select" aria-label="Default select example" name="rank" id="rank">
                  <option selected style="color: gray" disabled>Silahkan pilih peringkatnya...</option>
                  @for ($i = 1; $i <= 3; $i++) @if (!in_array($i, $existingRanks)) <option value="{{ $i }}">{{ $i }}
                    </option>
                    @endif
                    @endfor
                </select>
                @error('rank')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div class="mb-3">
                <label for="discount" class="form-label @error('discount') is-invalid @enderror">Diskon</label>
                <div class="input-group">
                  <input type="text" inputmode="numeric" class="form-control" id="discount" name="discount" required
                    autofocus placeholder="Masukkan diskon...">
                  <span class="input-group-text" id="inputGroupPrepend">%</span>
                </div>
                @error('discount')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div>
                <label for="image" class="form-label">Gambar Badge</label>
                <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image"
                  onchange="previewImage()">
                @error('image')
                <div class="invalid-feedback">
                  <p>{{ $message }}</p>
                </div>
                @enderror
              </div>
            </div>
            <div class="my-4" id="previewImgContainer">
              <p class="fw-bold text-center">Preview Gambar</p>
              <div class="d-flex justify-content-center">
                <img class="img-preview img-fluid mb-3" style="max-height: 200px">
              </div>
            </div>
            <div class="modal-footer">
              <a href="/dashboard/badge" class="btn btn-secondary me-1">Batal</a>
              <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  function previewImage() {
      const image = document.querySelector('#image');
      const imgPreview = document.querySelector('.img-preview');

      const oFReader = new FileReader();
      oFReader.readAsDataURL(image.files[0]);

      oFReader.onload = function(oFREvent) {
        imgPreview.src = oFREvent.target.result;
      }
    }
</script>
@endsection