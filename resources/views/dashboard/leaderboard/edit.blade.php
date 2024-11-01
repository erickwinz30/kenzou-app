@extends('dashboard.layout.main')

@section('container')
<div class="pagetitle">
  <h1>Badge Leaderboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Admin</li>
      <li class="breadcrumb-item active">Badge</li>
      <li class="breadcrumb-item active">Edit</li>
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
          <h5 class="card-title">Edit Badge Leaderboard</h5>
          <form action="/dashboard/badge-leaderboard/{{ $leaderboard->id }}" method="POST" id="editForm"
            enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div class="mb-3">
                <label for="badge_name" class="form-label @error('badge_name') is-invalid @enderror">Nama Badge</label>
                <input type="text" class="form-control" id="badge_name" name="badge_name"
                  placeholder="Masukkan nama badge..." value="{{ old('badge_name', $leaderboard->badge_name) }}"
                  required autofocus>
                @error('badge_name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="rank" class="form-label @error('rank') is-invalid @enderror">Untuk Peringkat</label>
                <select class="form-select" aria-label="Default select example" name="rank" id="rank">
                  <option value="1" {{ old('rank', $leaderboard->rank) == 1 ? 'selected' : '' }}>1</option>
                  <option value="2" {{ old('rank', $leaderboard->rank) == 2 ? 'selected' : '' }}>2</option>
                  <option value="3" {{ old('rank', $leaderboard->rank) == 3 ? 'selected' : '' }}>3</option>
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
                    autofocus placeholder="Masukkan diskon..."
                    value="{{ old('discount', $leaderboard->discount * 100) }}">
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
                @if ($leaderboard->image)
                <img class="img-preview img-fluid mb-3" style="max-height: 200px"
                  src="{{ asset('storage/' . $leaderboard->image) }}">
                @else
                <img class="img-preview img-fluid mb-3" style="max-height: 200px">
                @endif
              </div>
            </div>
            <div class="modal-footer">
              <a href="/dashboard/badge" class="btn btn-secondary me-1">Batal</a>
              <button type="button" class="btn btn-primary" onclick="editConfirmation()">Edit</button>
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

    function editConfirmation() {
    Swal.fire({
    title: "Yakin ingin mengubah data badge leaderboard?",
    text: "Aksi ini tidak bisa mengembalikan data!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#2980B9",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, update it!"
    }).then((result) => {
    if (result.isConfirmed) {
    document.getElementById('editForm').submit();
    }
    });
    };
</script>
@endsection
