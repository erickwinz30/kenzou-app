@extends('member.layout.main')

@section('container')
<div class="pagetitle">
  <h1>Selamat datang, {{ explode(' ', auth()->guard('member')->user()->nama)[0] }}!</h1>
</div><!-- End Page Title -->

<div class="col-12">
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2">
    <section class="section ">
      <div class="card shadow h-85" style="border-radius: 15px">
        <div class="card-body p-3">
          <div>
            <h5 class="card-title p-0 mb-4">Progres Anda</h5>
          </div>
          <div class="d-flex justify-content-around align-items-center mb-2" style="box-sizing: border-box">
            <div class="text-center">
              <div>
                <img src="{{ asset('storage/' . $badge->image) }}" alt="{{ $badge->image }}" style="max-width: 100px">
              </div>
              <div>
                <h2>{{ $badge->nama }}</h2>
              </div>
            </div>
            <div>
              <h5 class="card-text" style="position: relative; top: -20px;">{{ $member->experience_point }} XP</h5>
            </div>
          </div>
          @if ($nextBadge)
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="progress w-100" role="progressbar" aria-label="Basic example"
              aria-valuenow="{{ $member->experience_point }}" aria-valuemin="{{ $badge->min_point }}"
              aria-valuemax="{{ $badge->max_point }}">
              @php
              // Calculate the progress percentage
              $progressPercentage =
              (($member->experience_point - $badge->min_point) / ($badge->max_point - $badge->min_point)) *
              100;
              @endphp

              <div class="progress-bar" style="width: {{ $progressPercentage }}%;"></div>
            </div>
            <p class="card-text ms-2">{{ round($progressPercentage) }}%</p>
          </div>
          <div>
            <p class="m-0">Sisa {{ $nextBadge->min_point - $member->experience_point }} XP untuk mencapai
              {{ $nextBadge->nama }}!</p>
          </div>
          @else
          <p class="m-0 text-center">Selamat anda mencapai badge tertinggi!</p>
          @endif
        </div>
      </div>
    </section>

    @if($countedFinishedChallenge > 0 || $countedFinishedVoucher > 0)
    <section class="section">
      <div class="card shadow h-85" style="border-radius: 15px">
        <div class="card-body p-3">
          <h5 class="card-text">
            @if($countedFinishedChallenge > 0 && $countedFinishedVoucher)
            Anda memiliki {{ $countedFinishedChallenge }} challenge selesai dan {{ $countedFinishedVoucher }}
            voucher yang dapat dipakai
            @elseif($countedFinishedChallenge > 0)
            Anda memiliki {{ $countedFinishedChallenge }} challenge selesai yang dapat dipakai
            @elseif($countedFinishedVoucher)
            Anda memiliki {{ $countedFinishedVoucher }} voucher yang dapat dipakai
            @endif
          </h5>
        </div>
      </div>
    </section>
    @endif

    <section class="section">
      <div class="card shadow h-85" style="border-radius: 15px">
        <div class="card-body p-3">
          <h5 class="card-title p-0 mb-3">Tips & Tricks</h5>
          <ol class="list-group list-group-numbered">
            <li class="list-group-item">Kumpulkan point untuk mendapatkan benefit lebih</li>
            <li class="list-group-item">Kejar peringkat tertinggi untuk mendapatkan diskon</li>
            <li class="list-group-item">Tukar point dengan voucher</li>
            <li class="list-group-item">Selesaikan challenge untuk mendapatkan benefit pencucian</li>
          </ol>
        </div>
      </div>
    </section>
  </div>
</div>

{{-- <section class="section mt-4">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        <div class="col">
          <div class="card shadow h-85" style="border-radius: 15px">
            <div class="card-body p-3">
              <div>
                <a href="{{ route('challenge-index', ['challengeProgress' => $progress->id]) }}"
                  class="text-decoration-none" style="color: black">
                  <h5 class="card-title p-0">{{ $progress->challenge->description }}</h5>
                  <span style="color: green;">(selesai)</span>
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
                    <p class="card-text ms-2 text-decoration-none">100%</p>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      @if ($unfinishChallengeProgress->isNotEmpty())
      <h2 class="fs-5 fw-bold">Dalam Progress</h2>
      @endif
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        @foreach ($unfinishChallengeProgress as $progress)
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
</section> --}}
@endsection
