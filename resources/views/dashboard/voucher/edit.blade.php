@extends('dashboard.layout.main')

@section('container')
<div class="pagetitle">
  <h1>Voucher</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Admin</li>
      <li class="breadcrumb-item active">Voucher</li>
      <li class="breadcrumb-item active">Edit</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

@if (session()->has('error'))
<div class="row justify-content-center">
  <div class="alert alert-danger alert-dismissible fade show col-lg-10 justify-content-center" role="alert">
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
          <h5 class="card-title">Edit Voucher</h5>
          <form action="/dashboard/voucher/{{ $voucher->id }}" method="POST" id="editForm">
            @method('put')
            @csrf
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div class="mb-3">
                <label for="nama" class="form-label @error('nama') is-invalid @enderror">Nama / Deskripsi
                  Singkat</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $voucher->nama) }}"
                  required autofocus>
                @error('nama')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="description" class="form-label @error('description') is-invalid @enderror">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3" required
                  autofocus>{{ old('description', $voucher->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                  <div class="mb-3">
                    <label for="point_needed" class="form-label @error('point_needed') is-invalid @enderror">Point yang
                      Diperlukan</label>
                    <input type="text" inputmode="numeric" class="form-control" id="point_needed" name="point_needed"
                      value="{{ old('point_needed', $voucher->point_needed) }}" required autofocus>
                    @error('point_needed')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="inputGroupSelect02">Status</label>
                    <select class="form-select" id="inputGroupSelect02" name="is_active">
                      <option value="0" {{ old('is_active', $voucher->is_active) == 0 ? 'selected' : '' }}>Tidak
                      </option>
                      <option value="1" {{ old('is_active', $voucher->is_active) == 1 ? 'selected' : '' }}>Aktif
                      </option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="discount" class="form-label @error('discount') is-invalid @enderror">Diskon</label>
                <div class="input-group">
                  <input type="text" inputmode="numeric" class="form-control" id="discount" name="discount"
                    value="{{ old('discount', $voucher->discount * 100) }}" required autofocus>
                  <span class="input-group-text" id="inputGroupPrepend">%</span>
                </div>
                @error('discount')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mb-3">
              <div class="mb-3">
                <label for="minimum_transaction" class="form-label">Minimum Transaksi</label>
                <div class="input-group">
                  <span class="input-group-text" id="inputGroupPrepend">Rp</span>
                  <input type="text" inputmode="numeric"
                    class="form-control @error('minimum_transaction') is-invalid @enderror" id="minimum_transaction"
                    name="minimum_transaction" value={{ number_format(old('minimum_transaction',
                    $voucher->minimum_transaction), 0, ',', '.') }}
                  required autofocus>
                </div>
                @error('minimum_transaction')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div>
                <div class="text-center">
                  <label class="form-label">Tanggal Berlaku:</label>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 mb-3">
                  <div class="mb-3">
                    <label for="from_date" class="form-label">Dari</label>
                    <input class="form-control @error('from_date') is-invalid @enderror" type="datetime-local"
                      id="from_date" name="from_date"
                      value="{{ old('from_date', \Carbon\Carbon::parse($voucher->from_date)->format('Y-m-d\TH:i')) }}"
                      required autofocus>
                    @error('from_date')
                    <div class="invalid-feedback">
                      <p>{{ $message }}</p>
                    </div>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="to_date" class="form-label">Sampai</label>
                    <input class="form-control @error('to_date') is-invalid @enderror" type="datetime-local"
                      id="to_date" name="to_date"
                      value="{{ old('from_date', \Carbon\Carbon::parse($voucher->to_date)->format('Y-m-d\TH:i')) }}"
                      required autofocus>
                    @error('to_date')
                    <div class="invalid-feedback">
                      <p>{{ $message }}</p>
                    </div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <a href="/dashboard/voucher" class="btn btn-secondary me-1">Batal</a>
              <button type="button" class="btn btn-primary" onclick="editConfirmation()">Edit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  function editConfirmation() {
      Swal.fire({
        title: "Yakin ingin mengubah data voucher?",
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