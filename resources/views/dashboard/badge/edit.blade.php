@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Badge</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Badge</li>
        <li class="breadcrumb-item active">Edit</li>
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
            <h5 class="card-title">Tambah Badge</h5>
            <form action="/dashboard/badge/{{ $badge->id }}" method="POST" id="editForm"
              enctype="multipart/form-data">
              @method('PUT')
              @csrf
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mb-3">
                <div class="mb-3">
                  <label for="nama" class="form-label @error('nama') is-invalid @enderror">Nama Badge</label>
                  <input type="text" class="form-control" id="nama" name="nama"
                    value="{{ old('nama', $badge->nama) }}" required autofocus>
                  @error('nama')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 pe-0">
                  <div class="pe-0">
                    <label for="min_point" class="form-label @error('min_point') is-invalid @enderror">Min
                      Point</label>
                    <input type="text" inputmode="numeric" class="form-control" id="min_point" name="min_point"
                      value="{{ old('min_point', $badge->min_point) }}" required autofocus placeholder="Minimal point...">
                    @error('min_point')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="pe-0">
                    <label for="max_point" class="form-label @error('max_point') is-invalid @enderror">Max
                      Point</label>
                    <input type="text" inputmode="numeric" class="form-control" id="max_point" name="max_point"
                      value="{{ old('max_point', $badge->max_point) }}" required autofocus
                      placeholder="Maksimal point...">
                    @error('max_point')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-3">
                  <label for="discount" class="form-label @error('discount') is-invalid @enderror">Diskon</label>
                  <div class="input-group">
                    <input type="text" inputmode="numeric" class="form-control" id="discount" name="discount"
                      value="{{ old('discount', $badge->discount * 100) }}" required autofocus>
                    <span class="input-group-text" id="inputGroupPrepend">%</span>
                  </div>
                  @error('discount')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="image" class="form-label">Gambar Badge</label>
                  <input class="form-control @error('image') is-invalid @enderror" type="file" id="image"
                    name="image" onchange="previewImage()">
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
                  @if ($badge->image)
                    <img class="img-preview img-fluid mb-3" style="max-height: 200px"
                      src="{{ asset('storage/' . $badge->image) }}">
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

    //konfirmasi edit data
    function editConfirmation() {
      Swal.fire({
        title: "Yakin ingin mengubah data badge?",
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
