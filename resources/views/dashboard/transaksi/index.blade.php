@extends('dashboard.layout.main')

@section('container')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.3/css/dataTables.dateTime.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.bootstrap5.css">

  <div class="pagetitle">
    <h1>Transaksi</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Admin</a></li>
        <li class="breadcrumb-item"><a href="/transaksi">Transaksi</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <div class="row justify-content-center">
      <div class="alert alert-success alert-dismissible fade show col-lg-12 justify-content-center" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

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
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div>
              <h5 class="card-title">Data Transaksi</h5>
            </div>
            <div class="container">
              <div class="row mb-3">
                <div class="col-12 col-md-8 d-flex flex-wrap align-items-center mb-3 mb-md-0">
                  <i class="bi bi-calendar3 me-2"></i>
                  <input type="text" class="form-control me-2 w-auto" name="min" id="min"
                    style="max-width: 200px;">
                  <p class="card-text me-2 my-auto">S/D</p>
                  <input type="text" class="form-control me-2 w-auto" name="max" id="max"
                    style="max-width: 200px;">
                  <button type="button" id="tombolReset" class="btn btn-primary me-2">
                    <i class="bi bi-arrow-clockwise"></i>
                  </button>
                  <div class="btn-group mt-2" role="group" aria-label="Basic example">
                    <a href="/transaksi"
                      class="btn btn-primary {{ Request::is('transaksi') ? 'disabled' : '' }}">Today</a>
                    <a href="{{ route('transaksi.thisWeek') }}"
                      class="btn btn-primary {{ Request::is('transaksiThisWeek') ? 'disabled' : '' }}">This Week</a>
                    <a href="{{ route('transaksi.thisMonth') }}"
                      class="btn btn-primary {{ Request::is('transaksiThisMonth') ? 'disabled' : '' }}">This Month</a>
                    <a href="{{ route('transaksi.thisYear') }}"
                      class="btn btn-primary {{ Request::is('transaksiThisYear') ? 'disabled' : '' }}">This Year</a>
                  </div>
                </div>
                <div class="col-12 col-md-4 text-end">
                  <a href="/transaksiBaru" class="btn btn-success d-inline">
                    <i class="bi bi-plus me-2"></i>Transaksi
                  </a>
                </div>
              </div>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table" id="tabelTransaksi">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Transaksi ID</th>
                    <th>No. Telp Pelanggan</th>
                    <th>Nama Layanan</th>
                    <th>Kasir</th>
                    <th data-type="datetime" data-format="YYYY/DD/MM">Tanggal Transaksi</th>
                    <th>Metode Pembayaran</th>
                    <th>Keterangan</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody id="transaksiTableBody">
                  @if ($transaksis->isNotEmpty())
                    @foreach ($transaksis as $transaksi)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Str::limit($transaksi->id, 15) }}</td>
                        <td>{{ $transaksi->nomor_telepon }}</td>
                        <td>
                          @php
                            $namaLayanan = $transaksi->detail_layanan->pluck('layanan.nama_layanan')->toArray();
                            echo implode(', ', $namaLayanan);
                          @endphp
                        </td>
                        <td>{{ $transaksi->user->nama }}</td>
                        <td class="text-center align-middle" style="padding: 0;">
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 50px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                            {{ $transaksi->date }}
                          </span>
                        </td>
                        <td>{{ $transaksi->metode_pembayaran }}</td>
                        <td>{{ Str::limit($transaksi->keterangan, 20) }}</td>
                        <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        <td>
                          <a href="/transaksi/{{ $transaksi->id }}/edit" class="btn btn-warning"><i
                              class="bi bi-pencil"></i></a>
                          <form action="/transaksi/{{ $transaksi->id }}" method="POST" class="d-inline"
                            id="deleteForm{{ $transaksi->id }}">
                            @method('DELETE')
                            @csrf
                            <button type="button" class="btn btn-danger"
                              onclick="deleteConfirmation('{{ $transaksi->id }}')">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="10" class="text-center">No transactions found for the selected date range.</td>
                    </tr>
                  @endif
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="8" class="text-right">Total Penjualan:</th>
                    <th id="totalHarga">Rp 0</th>
                    <th colspan="1"></th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- End Table with stripped rows -->
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
  <script src="https://cdn.datatables.net/datetime/1.5.3/js/dataTables.dateTime.min.js"></script>
  <script src="https://cdn.datatables.net/fixedheader/4.0.1/js/dataTables.fixedHeader.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.bootstrap5.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.colVis.min.js"></script>
  <script>
    // Initialize minDate and maxDate variables
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
      var min = minDate.val();
      var max = maxDate.val();
      var dateStr = data[5]; // Ensure this index is correct

      // console.log('Date string from table:', dateStr); // Debug

      // Remove HTML tags and trim extra spaces
      dateStr = dateStr.replace(/<[^>]*>/g, '').trim();

      var date;

      // Adjust date parsing based on the expected format
      if (dateStr) {
        var dateParts = dateStr.split(' '); // Split by space for `YYYY-MM-DD HH:MM:SS` format
        var dateOnly = dateParts[0]; // Get the date part

        var dateParts = dateOnly.split('-'); // Split by '-'
        if (dateParts.length === 3) {
          var year = parseInt(dateParts[0], 10);
          var month = parseInt(dateParts[1], 10) - 1; // Months are 0-based
          var day = parseInt(dateParts[2], 10);
          date = new Date(year, month, day);
        } else {
          console.error('Unexpected date format:', dateStr);
        }
      }

      // Convert min and max to Date objects
      var minDateObj = min ? new Date(min) : null;
      var maxDateObj = max ? new Date(max) : null;

      if (
        (minDateObj === null && maxDateObj === null) ||
        (minDateObj === null && date <= maxDateObj) ||
        (minDateObj <= date && maxDateObj === null) ||
        (minDateObj <= date && date <= maxDateObj)
      ) {
        return true;
      }
      return false;
    });

    // Create date inputs
    minDate = new DateTime($('#min'), {
      format: 'YYYY-MM-DD'
    });
    maxDate = new DateTime($('#max'), {
      format: 'YYYY-MM-DD'
    });

    // DataTables initialization
    $(document).ready(function() {
      var table = $('#tabelTransaksi').DataTable({
        layout: {
          topStart: {
            buttons: ['csv', 'excel', 'print', 'colvis']
          }
        },
        fixedHeader: true,
        "footerCallback": function(row, data, start, end, display) {
          var api = this.api();

          // Remove formatting to get integer data for summation
          var intVal = function(i) {
            return typeof i === 'string' ?
              i.replace(/[\Rp,.]/g, '') * 1 :
              typeof i === 'number' ?
              i : 0;
          };

          // Total over all pages
          total = api
            .column(8)
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Total over this page
          pageTotal = api
            .column(8, {
              page: 'current'
            })
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Update footer
          $(api.column(8).footer()).html(
            'Rp ' + new Intl.NumberFormat('id-ID').format(pageTotal)
          );
        },
      });

      $('#min, #max').on('change', function() {
        table.draw();
      });

      // Reset button functionality
      $('#tombolReset').on('click', function() {
        $('#min').val('');
        $('#max').val('');
        minDate.val('');
        maxDate.val('');
        table.draw();
      });
    });
  </script>

  <script>
    //konfirmasi hapus data
    function deleteConfirmation(id) {
      Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Aksi ini tidak bisa mengembalikan data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#2980B9",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('deleteForm' + id).submit();
        }
      });
    }
  </script>
@endsection
