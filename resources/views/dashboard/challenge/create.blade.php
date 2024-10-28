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
                  name="description" required autofocus>
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
                      id="from_date" name="from_date" onchange="previewImage()">
                    @error('from_date')
                    <div class="invalid-feedback">
                      <p>{{ $message }}</p>
                    </div>
                    @enderror
                  </div>
                  <div>
                    <label for="to_date" class="form-label">Sampai</label>
                    <input class="form-control @error('to_date') is-invalid @enderror" type="datetime-local"
                      id="to_date" name="to_date" onchange="previewImage()">
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
              <div>
                <label for="target" class="form-label">Target</label>
                <input type="text" inputmode="numeric" class="form-control @error('target') is-invalid @enderror"
                  id="target" name="target" required autofocus>
                @error('target')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="mb-3 unit-input-container">
                <label for="unit" class="form-label">Satuan</label>
                <div class="input-group d-flex">
                  <select class="js-unit-tags" name="reward_unit_id" id="reward_unit_id">
                  </select>
                </div>
                @error('reward_unit_id')
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

<script>
  $(document).ready(function() {
      var $select = $(".js-unit-tags").select2({
        tags: true,
        placeholder: "Pilih atau masukkan unit baru...",
        allowClear: true,
        ajax: {
          url: '/dashboard/fetchUnits', // URL endpoint untuk memuat data
          dataType: 'json',
          delay: 250,
          data: function(params) {
            return {
              q: params.term // istilah pencarian
            };
          },
          processResults: function(data) {
            return {
              results: data.map(function(unit) {
                return {
                  id: unit.id,
                  text: unit.unit
                };
              })
            };
          },
          cache: true
        }
      });

      // Event listener untuk menghapus pilihan
      $select.on('select2:unselecting', function(e) {
        $(this).val(null).trigger('change');
      });
    });
</script>
@endsection