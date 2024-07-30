@extends('dashboard.layout.main')

@section('container')
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

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div>
              <h5 class="card-title">Data Transaksi</h5>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div>
                <a href="/layanan/create" type="button" class="btn btn-success d-inline">
                  <i class="bi bi-plus" style="margin-right: 2px;"></i>Transaksi
                </a>
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <input type="date" name="from_date" id="from_date">
                <p class="card-text mx-2 my-auto">S/D</p>
                <input type="date" name="to_date" id="to_date">
              </div>
            </div>

            <!-- Table with stripped rows -->
            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Transaksi ID</th>
                    <th>No. Telp Pelanggan</th>
                    <th>Nama Layanan</th>
                    <th>Kasir</th>
                    <th data-type="datetime" data-format="YYYY/DD/MM">Tanggal</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
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
                      {{-- <td>{{ Str::limit($layanan->detail, 20) }}</td> --}}
                      <td class="text-center align-middle" style="padding: 0;">
                        <span
                          style="color:#219653; background-color: #e8f4ed; border-radius: 50px; padding: 3px; display: inline-block;">
                          {{ $transaksi->date }}
                        </span>
                      </td>
                      <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                      <td>{{ $transaksi->metode_pembayaran }}</td>
                      <td>{{ Str::limit($transaksi->keterangan, 20) }}</td>
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
