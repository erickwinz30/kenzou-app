@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Transaksi Baru</h1>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <div class="row justify-content-center">
      <div class="alert alert-success alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="row">

          <!-- Layanan Select -->
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="layanan-header">
                  <h5 class="card-title">Pilih Layanan</h5>
                  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                    @foreach ($layanans as $layanan)
                      <div class="col">
                        <div class="card shadow h-85" style="border-radius: 15px">
                          <div class="card-body">
                            <h5 class="card-title">{{ $layanan->nama_layanan }}</h5>
                            <p class="card-text">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                            <button href="#" class="btn btn-primary add-item" data-id="{{ $layanan->id }}"
                              data-name="{{ $layanan->nama_layanan }}" data-price="{{ $layanan->harga }}">
                              Tambah
                            </button>
                          </div>
                        </div>
                      </div>
                    @endforeach
                    <!-- Add more cards here -->
                  </div>
                </div>
                <!-- Additional content here -->
              </div>
            </div>
          </div>
          <!-- End Layanan Select -->


          <!-- Pelanggan Select -->
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="layanan-header">
                  <h5 class="card-title">Informasi Pelanggan</h5>
                  <div class="item">
                    <div class="input-group flex-nowrap">
                      <span class="input-group-text" id="addon-wrapping">+62</span>
                      <input type="text" inputmode="numeric" class="form-control" id="nomor_telepon"
                        placeholder="No. telepon" aria-label="Username" aria-describedby="addon-wrapping">
                      <button class="btn btn-primary" id="tambahNoPelanggan">Tambah</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- End Pelanggan Select -->
        </div>
      </div><!-- End Left side columns -->

      <!-- Right side columns -->
      <div class="col-lg-4">

        <!-- Detail Transaksi -->
        <div class="card">
          <div class="card-body">
            <form action="/transaksiBaru" method="POST">
              @csrf
              <h5 class="card-title">Detail Transaksi</h5>
              {{-- Container detail layanan pada transaksi --}}
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="card-text fw-semibold">Tgl Transaksi</h6>
                <p class="card-text">{{ $tanggal_transaksi }}</p>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-2" id="containerNoPelanggan">
                <h6 class="card-text fw-semibold my-auto">No. Pelanggan</h6>
              </div>
              <h6 class="card-text fw-semibold" id="card_item">Item</h6>
              <div class="item_transaksi mb-3" id="item_transaksi">
                <!-- Items will be added here dynamically -->
              </div>
              <div class="mb-3">
                <h6 class="card-text fw-semibold mb-3" id="card_item">Keterangan</h6>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Silahkan diisi disini.."></textarea>
              </div>
              {{-- Container Subtotal & Metode Pembayaran --}}
              <div class="card-body shadow" style="background-color: #EEEEEE; border-radius: 15px">
                <div class="d-flex justify-content-between align-items-center">
                  <h6 class="card-text fw-semibold mt-4">Subtotal: </h6>
                  <input type="hidden" name="total_harga" value="0" id="inputHarga">
                  <p class="card-text" id="subtotal_value">Rp. 0</p>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <h6 class="card-text fw-semibold mt-3">Metode Pembayaran: </h6>
                  <div class="d-flex justify-content-end align-items-center">
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
              <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div><!-- End Detail Transaksi -->

      </div><!-- End Right side columns -->

    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Select all the "Tambah" buttons
      const addItemButtons = document.querySelectorAll('.add-item');
      const addNoPelanggan = document.getElementById('tambahNoPelanggan');
      const containerItem = document.getElementById('item_transaksi');
      const subtotalElement = document.getElementById('subtotal_value');
      let inputHarga = document.getElementById('inputHarga');

      let subtotal = 0;

      // fuction untuk update subtotal
      function updateSubtotal(amount) {
        subtotal += amount;
        inputHarga.value = subtotal;
        subtotalElement.textContent = `Rp. ${subtotal.toLocaleString()}`;
      }

      // Add click event listeners to each button
      addItemButtons.forEach(button => {
        button.addEventListener('click', function(event) {
          event.preventDefault();

          // Retrieve data attributes from the clicked button
          const itemId = this.getAttribute('data-id');
          const itemName = this.getAttribute('data-name');
          const itemPrice = parseInt(this.getAttribute('data-price'));

          // Create a new div to hold the item details
          const itemDiv = document.createElement('div');
          itemDiv.classList.add('item', 'd-flex', 'justify-content-between', 'align-items-center');

          // Add the item name and price to the new div
          itemDiv.innerHTML = `
            <input type="hidden" name="layanan_id[]" value="${itemId}">
            <p class="card-text my-auto">${itemName}</p>
            <div class="d-flex justify-content-end align-items-center">
            <p class="card-text my-auto">Rp. ${itemPrice}</p>
            <button type="button" class="btn p-0 ms-2 remove-item">
              <i class="bi bi-x-circle"></i>
            </button>
            </div>
          `;

          button.disabled = true;

          // Append the new item div to the transaction container
          // containerTransaksi = document.getElementById('item_transaksi');
          containerItem.appendChild(itemDiv);

          updateSubtotal(itemPrice);

          // Add event listener to the remove button
          itemDiv.querySelector('.remove-item').addEventListener('click', function() {
            itemDiv.remove();
            updateSubtotal(-itemPrice);
            button.disabled = false;
          });
        });
      });

      addNoPelanggan.addEventListener('click', function(event) {
        event.preventDefault();

        let noHp = document.getElementById('nomor_telepon').value;

        let infoPelanggan = document.createElement('div');

        infoPelanggan.classList.add('d-flex', 'justify-content-end', 'align-items-center')

        infoPelanggan.innerHTML = `
          <input type="hidden" name="nomor_telepon" value="${noHp}">
          <p class="card-text my-auto">${noHp}</p>
          <button type="button" class="btn p-0 ms-2 my-auto" id="remove-pelanggan">
            <i class="bi bi-x-circle"></i>
          </button>
        `;

        document.getElementById('containerNoPelanggan').appendChild(infoPelanggan);

        document.getElementById('remove-pelanggan').addEventListener('click', function() {
          // event.preventDefault();
          infoPelanggan.remove();
        });
      });
    });
  </script>
@endsection
