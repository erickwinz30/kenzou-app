@extends('member.layout.main')

@section('container')
<div class="pagetitle">
  <h1>Progress Challenge</h1>
</div><!-- End Page Title -->

<section class="section mt-4">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        @foreach ($challengeProgress as $progress)
        <div class="col">
          <div class="card shadow h-85" style="border-radius: 15px">
            <div class="card-body p-3">
              <div>
                <a href="{{ route('challenge-index', ['challengeProgress' => $progress->id]) }}"
                  class="text-decoration-none" style="color: black">
                  <h5 class="card-title p-0">{{ $progress->challenge->description }}</h5>
                  <p class="card-text mt-3 text-decoration-none">
                    Berlaku hingga: {{ \Carbon\Carbon::parse($progress->challenge->to_date)->format('d F Y') }}
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="progress w-100" role="progressbar" aria-label="Basic example"
                      aria-valuenow="{{ $progress->progress }}" aria-valuemin="0"
                      aria-valuemax="{{ $progress->challenge->target }}">
                      @php
                      $progressPercentage = ($progress->progress / $progress->challenge->target) * 100;
                      @endphp
                      <div class="progress-bar" style="width: {{ $progressPercentage }}%;"></div>
                    </div>
                    <p class="card-text ms-2 text-decoration-none">{{ $progressPercentage }}%</p>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection