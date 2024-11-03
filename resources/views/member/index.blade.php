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
                <h5 class="card-title p-0">{{ $progress->challenge->description }}</h5>
              </div>
              {{-- <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="progress w-100" role="progressbar" aria-label="Basic example"
                    aria-valuenow="{{ $information->experience_point }}" aria-valuemin="{{ $badge->min_point }}"
                    aria-valuemax="{{ $badge->max_point }}">
                    @php
                    // Calculate the progress percentage
                    $progressPercentage = (($information->experience_point - $badge->min_point) /
                    ($badge->max_point - $badge->min_point)) * 100;
                    @endphp

                    <div class="progress-bar" style="width: {{ $progressPercentage }}%;"></div>
                  </div>
                  <p class="card-text ms-2">{{ round($progressPercentage) }}%</p>
                </div>
              </div> --}}
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection
