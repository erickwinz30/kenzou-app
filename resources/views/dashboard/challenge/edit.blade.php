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
          <h5 class="card-title">Edit Challenge</h5>
          <form action="/dashboard/challenge/{{ $challenge->id }}" method="POST" id="editForm">
            @method('put')
            @csrf
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                  name="description" value="{{ old('description', $challenge->description) }}" required autofocus>
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
                      id="from_date" name="from_date"
                      value="{{ old('from_date', \Carbon\Carbon::parse($challenge->from_date)->format('Y-m-d\TH:i')) }}">
                    @error('from_date')
                    <div class="invalid-feedback">
                      <p>{{ $message }}</p>
                    </div>
                    @enderror
                  </div>
                  <div>
                    <label for="to_date" class="form-label">Sampai</label>
                    <input class="form-control @error('to_date') is-invalid @enderror" type="datetime-local"
                      id="to_date" name="to_date"
                      value="{{ old('to_date', \Carbon\Carbon::parse($challenge->to_date)->format('Y-m-d\TH:i')) }}">
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
                  id="target" name="target" value="{{ old('target', $challenge->target) }}" required autofocus>
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
                    <option value="{{ $challenge->reward_unit_id }}" selected>{{ $challenge->reward_unit_name }}
                    </option>
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
              <button type="button" class="btn btn-primary" onclick="editConfirmation()">Edit</button>
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

      // Set nilai awal pada Select2
      var initialUnitId = "{{ $challenge->rewardUnit->id }}";
      var initialUnitText = "{{ $challenge->rewardUnit->unit }}";
      if (initialUnitId && initialUnitText) {
        var option = new Option(initialUnitText, initialUnitId, true, true);
        $select.append(option).trigger('change');
      }
    });

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
