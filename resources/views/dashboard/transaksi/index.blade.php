@extends('dashboard.layout.main')

@section('container')
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
  {{--
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.3/css/dataTables.dateTime.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.bootstrap5.css">

  <div class="pagetitle">
    <h1>Transaksi</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard/admin">Admin</a></li>
        <li class="breadcrumb-item active"><a href="/dashboard/transaksi">Transaksi</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <x-alert-success :message="session('success')" />
  @endif

  @if (session()->has('error'))
    <x-alert-error :message="session('error')" />
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div>
              <h5 class="card-title">Data Transaksi</h5>
            </div>
            <div class="row align-items-center mb-3 card">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-md-8 mt-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                      <form action="/dashboard/transaksi/searchFromDate" method="POST" id="searchFromDateForm"
                        class="d-flex align-items-center gap-2">
                        @csrf
                        <i class="bi bi-calendar3 fs-5"></i>
                        <input type="date" class="form-control" name="min_date" id="min_date" placeholder="Dari"
                          style="max-width: 150px;" required>
                        <span class="text-muted">S/D</span>
                        <input type="date" class="form-control" name="max_date" id="max_date" placeholder="Sampai"
                          style="max-width: 150px;" required>
                        <button type="submit" id="submitButton" class="btn btn-primary">
                          <i class="bi bi-search"></i>
                        </button>
                      </form>
                      <a href="/dashboard/transaksi" type="button" id="tombolReset" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                      </a>
                    </div>
                  </div>
                  <div class="col-12 col-md-4 mt-3">
                    <div class="btn-group w-100 w-md-auto" role="group">
                      <a href="/dashboard/transaksi"
                        class="btn btn-outline-primary {{ Request::is('dashboard/transaksi') ? 'active' : '' }}">Today</a>
                      <a href="{{ route('transaksi.thisWeek') }}"
                        class="btn btn-outline-primary {{ Request::is('dashboard/transaksiThisWeek') ? 'active' : '' }}">This
                        Week</a>
                      <a href="{{ route('transaksi.thisMonth') }}"
                        class="btn btn-outline-primary {{ Request::is('dashboard/transaksiThisMonth') ? 'active' : '' }}">This
                        Month</a>
                      <a href="{{ route('transaksi.thisYear') }}"
                        class="btn btn-outline-primary {{ Request::is('dashboard/transaksiThisYear') ? 'active' : '' }}">This
                        Year</a>
                    </div>
                  </div>
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
                    <th>Pelanggan</th>
                    <th>Nama Layanan</th>
                    <th>Kasir</th>
                    <th data-type="datetime" data-format="YYYY/DD/MM">Tanggal Transaksi</th>
                    <th>Metode Pembayaran</th>
                    <th>Nomor Polisi</th>
                    <th>Keterangan</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody id="transaksiTableBody">
                  @if ($transaksis->isNotEmpty())
                    @foreach ($transaksis as $transaksi)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Str::limit($transaksi->id, 15) }}</td>
                        <td>
                          @if ($transaksi->pelanggan->member_id)
                            {{ $transaksi->pelanggan->member->nama }}
                          @elseif (!$transaksi->pelanggan->member_id)
                            {{ $transaksi->pelanggan->nomor_telepon }}
                          @endif
                        </td>
                        <td>
                          @php
                            $namaLayanan = $transaksi->detail_layanan->pluck('layanan.nama_layanan')->toArray();
                            echo implode(', ', $namaLayanan);
                          @endphp
                        </td>
                        <td>{{ $transaksi->user->nama }}</td>
                        <td class="text-center align-middle" style="padding: 0;">
                          <span
                            style="color:#219653; background-color: #e8f4ed; border-radius: 10px; padding: 3px 5px; display: inline-block; box-sizing: border-box">
                            {{ \Carbon\Carbon::parse($transaksi->date)->format('d-m-Y H:i:s') }}
                          </span>
                        </td>
                        <td>{{ $transaksi->metode_pembayaran }}</td>
                        <td>{{ $transaksi->nomor_polisi }}</td>
                        <td>{{ Str::limit($transaksi->keterangan, 20) }}</td>
                        <td>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
                        <td>
                          <a href="/dashboard/transaksi/{{ $transaksi->id }}" class="btn btn-info"><i
                              class="bi bi-eye"></i></a>
                          <a href="/dashboard/transaksi/{{ $transaksi->id }}/edit" class="btn btn-warning"><i
                              class="bi bi-pencil"></i></a>
                          <form action="/dashboard/transaksi/{{ $transaksi->id }}" method="POST" class="d-inline"
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
                    {{-- <tr>
                  <td colspan="10" class="text-center">No transactions found for the selected date range.</td>
                </tr> --}}
                  @endif
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="9" class="text-right">Total Penjualan:</th>
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
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
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
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

  <script>
    $(document).ready(function() {
      var table = $('#tabelTransaksi').DataTable({
        scrollX: true,
        columns: [{
            data: 'no',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'id',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'pelanggan',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'nama_layanan',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'kasir',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'tanggal_transaksi',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'metode_pembayaran',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'nomor_polisi',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'keterangan',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'subtotal',
            defaultContent: '<i>Not set</i>'
          },
          {
            data: 'action',
            defaultContent: '<i>Not set</i>'
          },
        ],
        dom: 'Bfrtip', // Menambahkan tombol ekspor ke DOM
        buttons: [{
            extend: 'csv',
            text: 'CSV',
            exportOptions: {
              modifier: {
                search: 'none',
                page: 'all'
              }
            }
          },
          {
            extend: 'excel',
            text: 'Excel',
            exportOptions: {
              modifier: {
                search: 'none',
                page: 'all'
              }
            }
          },
          {
            extend: 'pdf',
            text: 'PDF',
            exportOptions: {
              modifier: {
                search: 'none',
                page: 'all'
              }
            }
          },
          {
            extend: 'print',
            text: 'Print',
            exportOptions: {
              modifier: {
                search: 'none',
                page: 'all'
              }
            }
          },
          'colvis'
        ],
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
          var total = api
            .column(9)
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Update footer
          $(api.column(9).footer()).html(
            'Rp ' + new Intl.NumberFormat('id-ID').format(total)
          );
        },
        // Tambahkan ini untuk memastikan semua data dimuat
        "deferRender": false,
        "processing": true,
        "serverSide": false,
        "lengthMenu": [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ]
      });

      // Reset button functionality (updated)
      $('#tombolReset').on('click', function() {
        table.search('').columns().search('').draw();
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
