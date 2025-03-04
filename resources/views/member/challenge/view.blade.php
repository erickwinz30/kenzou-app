@extends('member.layout.main')

@section('container')
<div class="pagetitle d-flex justify-content-between align-items-center">
  <h1>Detail Challenge</h1>
  <a href="/challenge-progress" type="button" class="btn btn-primary"
    style="background-color: #012970; border-color:#012970">Kembali</a>
</div><!-- End Page Title -->

<section class="section mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="mt-3">
          <h2 class="fs-5 fw-bold">{{ $progress->challenge->description }}</h2>
        </div>
        <div>
          <p>Berlaku hingga {{ \Carbon\Carbon::parse($progress->challenge->to_date)->format('d M Y') }}</p>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
          <div>
            <h2 class="fs-5 fw-bold">Progress Challenge</h2>
            <p>
              {{ $progress->progress > $progress->challenge->target ? $progress->challenge->target : $progress->progress
              }}/{{ $progress->challenge->target }}
              {{ $progress->challenge->unit }}</p>
          </div>
          <div>
            <h2 class="fs-5 fw-bold">Hadiah yang diperoleh</h2>
            <p>{{ $progress->challenge->layanan->nama_layanan }}</p>
          </div>
        </div>
        <div>
          <h2 class="fs-5 fw-bold">Progress</h2>
          <div class="d-flex justify-content-between align-items-center">
            <div class="progress w-100" role="progressbar" aria-label="Basic example"
              aria-valuenow="{{ $progress->progress }}" aria-valuemin="0"
              aria-valuemax="{{ $progress->challenge->target }}">
              @php
              $progressPercentage = ($progress->progress / $progress->challenge->target) * 100;
              @endphp
              <div class="progress-bar" style="width: {{ $progressPercentage }}%;"></div>
            </div>
            @if ($progress->is_completed == 1)
            <p class="card-text ms-2 text-decoration-none">100%</p>
            @else
            <p class="card-text ms-2 text-decoration-none">{{ $progressPercentage }}%</p>
            @endif
          </div>
        </div>
        @if ($progress->is_completed == 1)
        <div class="mt-2">
          <p class="card-text">*silahkan gunakan benefit ini saat melakukan pembayaran pencucian</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection