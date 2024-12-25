@extends('dashboard.layout.main')

@section('container')
  <style>
    .select2-container {
      width: 100% !important;
    }
  </style>

  <div class="pagetitle">
    <h1>Challenge</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Challenge</li>
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
            <h5 class="card-title">Tambah Challenge</h5>
            <form action="/dashboard/challenge" method="POST" id="addForm" enctype="multipart/form-data">
              @csrf
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-3">
                  <label for="description" class="form-label">Deskripsi</label>
                  <input type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                    name="description" placeholder="Masukkan deksripsinya.." required autofocus>
                  @error('description')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div>
                  <label class="form-label">Tanggal Berlaku:</label>
                  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mb-3">
                    <div class="mb-3">
                      <label for="from_date" class="form-label">Dari</label>
                      <input class="form-control @error('from_date') is-invalid @enderror" type="datetime-local"
                        id="from_date" name="from_date">
                      @error('from_date')
                        <div class="invalid-feedback">
                          <p>{{ $message }}</p>
                        </div>
                      @enderror
                    </div>
                    <div>
                      <label for="to_date" class="form-label">Sampai</label>
                      <input class="form-control @error('to_date') is-invalid @enderror" type="datetime-local"
                        id="to_date" name="to_date">
                      @error('to_date')
                        <div class="invalid-feedback">
                          <p>{{ $message }}</p>
                        </div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-3">
                  <label for="unit" class="form-label">Satuan</label>
                  <div class="input-group d-flex">
                    <select class="form-select @error('unit') is-invalid @enderror" name="unit" id="unit"
                      aria-label="Default select example">
                      <option style="color: grey" selected disabled>Pilih satuannya...</option>
                      <option value="Transaksi">Per Transaksi</option>
                      <option value="Total Pengeluaran Member">Total pengeluaran member selama periode</option>
                    </select>
                  </div>
                  @error('unit')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="target" class="form-label">Target</label>
                  <input type="text" inputmode="numeric" class="form-control @error('target') is-invalid @enderror"
                    id="target" name="target" placeholder="Isi dalam bentuk angka.." required autofocus>
                  @error('target')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-3">
                  <label for="layanan_id" class="form-label">Hadiah yang diperoleh member</label>
                  <select class="form-select @error('layanan_id') is-invalid @enderror"
                    aria-label="Default select example" name="layanan_id" id="layanan_id">
                    <option selected style="color: gray" disabled>Silahkan pilih hadiahnya...</option>
                    {{-- <option value="Pencucian">Pencucian</option> --}}
                    @foreach ($categories as $category)
                      <optgroup label="{{ $category->name }}">
                        @foreach ($category->layanans as $layanan)
                          <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}
                            {{ $layanan->is_active ? '' : '(Tidak aktif)' }}
                          </option>
                        @endforeach
                      </optgroup>
                    @endforeach
                  </select>
                  @error('layanan_id')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="modal-footer">
                <a href="/dashboard/challenge" class="btn btn-secondary me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Tambah</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
