@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Transaksi</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Admin</a></li>
        <li class="breadcrumb-item"><a href="/transaksi">Transaksi</a></li>
        <li class="breadcrumb-item"><a href="/transaksi/{{ $transaksi->id }}/edit">Edit Transaksi</a></li>
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
            <h5 class="card-title">Edit Transaksi</h5>
            <form action="/transaksi/{{ $transaksi->id }}" method="POST">
              @method('put')
              @csrf
              <div class="mb-2 d-flex">
                <label for="id_transaksi" class="form-label me-3 @error('id_transaksi') is-invalid @enderror">ID
                  Transaksi: </label>
                <p>{{ $transaksi->id }}</p>
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
                <div class="mb-3">
                  <label for="user_id" class="form-label @error('user_id') is-invalid @enderror">Kasir</label>
                  <select class="form-select" id="user_id" name="user_id">
                    @foreach ($users as $user)
                      @if (old('user_id', $transaksi->user->id) == $user->id)
                        <option value="{{ $user->id }}" selected>{{ $user->nama }}</option>
                      @else
                        <option value="{{ $user->id }}">{{ $user->nama }}</option>
                      @endif
                    @endforeach
                  </select>
                  @error('user_id')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="nomor_telepon" class="form-label @error('nomor_telepon') is-invalid @enderror">No.
                    Telepon</label>
                  <input type="text" inputmode="numeric" class="form-control" id="nomor_telepon" name="nomor_telepon"
                    value="{{ old('nomor_telepon', $transaksi->nomor_telepon) }}" required autofocus>
                  @error('nomor_telepon')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div>
                <div class="d-flex justify-content-between mb-2">
                  <label for="layanan" class="form-label @error('layanan') is-invalid @enderror">Layanan: </label>
                  <button type="button" class="btn px-2 py-0 ms-2 btn-primary" id="addLayananBtn">
                    <i class="bi bi-plus-circle"></i>
                  </button>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3" id="layananContainer">
                  @foreach ($transaksi->detail_layanan as $detailLayanan)
                    <div class="mb-3">
                      <select class="form-select mb-2 layanan-select" name="layanan[]" id="layanan">
                        @foreach ($layanans as $layanan)
                          <option value="{{ $layanan->id }}" data-price="{{ $layanan->harga }}"
                            {{ old('layanan', $detailLayanan->layanan_id) == $layanan->id ? 'selected' : '' }}>
                            {{ $layanan->nama_layanan }}
                          </option>
                        @endforeach
                        <option value="">-- Kosong --</option>
                      </select>
                    </div>
                  @endforeach
                </div>
              </div>
              <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2">
                <div class="mb-3">
                  <label for="date" class="form-label @error('date') is-invalid @enderror">Tgl Transaksi</label>
                  <input type="datetime-local" class="form-control" id="date" name="date"
                    value="{{ old('date', $transaksi->date) }}" required autofocus>
                  @error('date')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="mb-3 mx-0">
                  <p>Metode Pembayaran</p>
                  <div class="d-flex justify-content-start align-items-center">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="metode_pembayaran" id="exampleRadios1"
                        value="tunai" checked>
                      <label class="form-check-label" for="exampleRadios1">
                        Tunai
                      </label>
                    </div>
                    <div class="form-check ms-3">
                      <input class="form-check-input" type="radio" name="metode_pembayaran" id="exampleRadios2"
                        value="qris">
                      <label class="form-check-label" for="exampleRadios2">
                        QRIS
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="keterangan" class="form-label @error('keterangan') is-invalid @enderror">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Silahkan diisi disini..">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                @error('keterangan')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 d-flex justify-content-end">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <label for="total_harga" class="form-label @error('total_harga') is-invalid @enderror">Total
                    Harga:</label>
                  <div class="input-group w-50">
                    <span class="input-group-text" id="mataUang">Rp</span>
                    <input type="text" inputmode="numeric" class="form-control" id="total_harga"
                      name="total_harga" value="{{ old('total_harga', $transaksi->total_harga) }}"
                      aria-describedby="mataUang" required autofocus>
                    @error('total_harga')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <a href="/transaksi" class="btn btn-secondary me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const layananContainer = document.getElementById('layananContainer');

      document.getElementById('addLayananBtn').addEventListener('click', function(event) {
        event.preventDefault();

        const divDivider = document.createElement('div');
        const newSelect = document.createElement('select');

        divDivider.className = 'mb-2';

        newSelect.className = 'form-select mb-2 layanan-select';
        newSelect.name = 'layanan[]';
        newSelect.innerHTML = `
            @foreach ($layanans as $layanan)
              <option value="{{ $layanan->id }}" data-price="{{ $layanan->harga }}">{{ $layanan->nama_layanan }}</option>
            @endforeach
            <option value="">-- Kosong --</option>
        `;

        divDivider.appendChild(newSelect);
        layananContainer.appendChild(divDivider);

        // Attach the event listener to the new dropdown
        newSelect.addEventListener('change', updateTotalHarga);

        // Update total price after adding new service
        updateTotalHarga();
      });

      function updateTotalHarga() {
        let totalHarga = 0;
        document.querySelectorAll('.layanan-select').forEach(selectElement => {
          const selectedOption = selectElement.selectedOptions[0];
          const harga = selectedOption.getAttribute('data-price');
          if (harga) {
            totalHarga += parseFloat(harga);
          }
        });
        console.log(totalHarga);
        document.getElementById('total_harga').value = totalHarga;
      }

      // Attach event listeners to initial dropdowns
      document.querySelectorAll('.layanan-select').forEach(selectElement => {
        selectElement.addEventListener('change', updateTotalHarga);
      });

      // Initial call to set the total price if there are pre-selected options
      updateTotalHarga();
    });
  </script>
@endsection
