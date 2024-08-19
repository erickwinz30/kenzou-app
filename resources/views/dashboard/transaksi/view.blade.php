@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Transaksi</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard/admin">Admin</a></li>
        <li class="breadcrumb-item"><a href="/dashboard/transaksi">Transaksi</a></li>
        <li class="breadcrumb-item"><a href="/dashboard/transaksi/{{ $transaksi->id }}">Detail Transaksi</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
@endsection
