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
    <x-alert-error type="success" :message="session('success')" />
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Tambah Layanan</h5>
            <form action="/dashboard/layanan" method="POST">
              @csrf
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-3">
                  <label for="nama_layanan" class="form-label @error('nama_layanan') is-invalid @enderror">Nama
                    Layanan</label>
                  <input type="text" class="form-control" id="nama_layanan" name="nama_layanan"
                    value="{{ old('nama_layanan') }}" required autofocus>
                  @error('nama_layanan')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="category_layanan_id" class="form-label">Kategori Layanan</label>
                  <select class="form-select @error('category_layanan_id') is-invalid @enderror"
                    aria-label="Default select example" name="category_layanan_id" id="category_layanan_id">
                    <option selected style="color: gray" disabled>Silahkan pilih kategori layanan...</option>
                    {{-- <option value="Pencucian">Pencucian</option> --}}
                    @foreach ($categories as $category)
                      @if ($category->is_active)
                        <option value="{{ $category->id }}" label="{{ $category->name }}">
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                  <div class="mb-3">
                    <label for="harga" class="form-label @error('harga') is-invalid @enderror">Harga</label>
                    <input type="text" inputmode="numeric" class="form-control" id="harga" name="harga"
                      value="{{ old('harga') }}" required autofocus>
                    @error('harga')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="point" class="form-label @error('point') is-invalid @enderror">Point</label>
                    <input type="text" inputmode="numeric" class="form-control" id="point" name="point"
                      value="{{ old('point') }}" required autofocus>
                    @error('point')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="mb-3">
                  <label for="detail" class="form-label @error('detail') is-invalid @enderror">Deskripsi Layanan</label>
                  <textarea class="form-control" id="detail" name="detail" value="{{ old('detail') }}" required autofocus></textarea>
                  @error('detail')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="modal-footer">
                <a href="/dashboard/layanan" class="btn btn-secondary me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Tambah</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
