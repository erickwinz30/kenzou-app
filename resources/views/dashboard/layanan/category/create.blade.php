@extends('dashboard.layout.main')

@section('container')
<div class="pagetitle">
  <h1>Layanan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Admin</li>
      <li class="breadcrumb-item">Layanan</li>
      <li class="breadcrumb-item">Tambah</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

@if (session()->has('success'))
<x-alert-error type="success" :message="session('success')" />
@endif

<section class="section">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Tambah Kategori Layanan</h5>
          <form action="/dashboard/category-layanan" method="POST">
            @csrf
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
              <div class="mb-3">
                <label for="name" class="form-label @error('name') is-invalid @enderror">Nama
                  Kategori Layanan</label>
                <input type="text" class="form-control" id="name" name="name" required autofocus>
                @error('name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
              </div>
              <div class="modal-footer">
                <a href="/dashboard/layanan" class="btn btn-secondary me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Tambah</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection