@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Layanan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="/layanan">Layanan</a></li>
        <li class="breadcrumb-item"><a href="{{ route('layanan.history') }}">History</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between" data-bs-toggle='modal'
              data-bs-target='#inputModal'>
              <h5 class="card-title">Data History Layanan</h5>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Detail</th>
                    <th data-type="datetime" data-format="YYYY/DD/MM">Tanggal Dibuat</th>
                    <th data-type="datetime" data-format="YYYY/DD/MM">Tanggal Terakhir Update</th>
                    <th data-type="datetime" data-format="YYYY/DD/MM">Tanggal Dihapus</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($layanans as $layanan)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $layanan->nama_layanan }}</td>
                      <td>Rp. {{ number_format($layanan->harga, 0, ',', '.') }}</td>
                      <td>{{ Str::limit($layanan->detail, 20) }}</td>
                      {{-- <td class="bg-success rounded-pill">{{ $layanan->added_date }}</td> --}}
                      <td>
                        <span
                          style="color:#219653; background-color: #e8f4ed; border-radius: 50px; padding: 5px 10px; display: inline-block;">
                          {{ $layanan->added_date }}
                        </span>
                      </td>
                      <td>
                        @if ($layanan->updated_date)
                          <span
                            style="color:#FFB22C; background-color: #F3FEB8; border-radius: 50px; padding: 5px 10px; display: inline-block;">
                            {{ $layanan->updated_date }}
                          </span>
                        @else
                          -
                        @endif
                      </td>
                      <td>
                        @if ($layanan->deleted_date)
                          <span
                            style="color:#C80036; background-color: #FA7070; border-radius: 50px; padding: 5px 10px; display: inline-block;">
                            {{ $layanan->deleted_date }}
                          </span>
                        @else
                          -
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- End Table with stripped rows -->
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
