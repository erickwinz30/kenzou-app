@extends('member.layout.main')

@section('container')
  <div class="pagetitle d-flex justify-content-center my-3">
    <h1 class="fs-2 fw-bold">LEADERBOARD</h1>
  </div><!-- End Page Title -->

  <section class="section d-flex justify-content-center mb-5">
    <div class="col col-lg-10">
      @foreach ($members as $member)
        <div class="card {{ $member->id === Auth::guard('member')->user()->id ? 'border border-primary' : '' }} shadow">
          <div class="card-body mt-4">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                @if ($loop->iteration == 1)
                  <img src="{{ asset('storage/' . $rankFirst) }}" alt="Badge Rank First" style="max-width: 75px">
                @elseif ($loop->iteration == 2)
                  <img src="{{ asset('storage/' . $rankSecond) }}" alt="Badge Rank Second" style="max-width: 75px">
                @elseif($loop->iteration == 3)
                  <img src="{{ asset('storage/' . $rankThird) }}" alt="Badge Rank Third" style="max-width: 75px">
                @else
                  {{ $loop->iteration }}
                @endif
                <p class="card-title ms-3">{{ $member->nama }}</p>
              </div>
              <div class="d-flex justify-content-end">
                <p class="card-title">{{ $member->experience_point }} Point</p>
              </div>
            </div>
          </div>
        </div>
      @endforeach

      @if (!$members->contains('id', Auth::guard('member')->user()->id))
        <div class="d-flex justify-content-center">
          <i class="bi bi-three-dots"></i>
        </div>
        <div class="card border border-primary shadow">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <p class="card-title ms-3">{{ $ownRank }}</p>
                <p class="card-title">{{ Auth::guard('member')->user()->nama }}</p>
              </div>
            </div>
            <div class="d-flex justify-content-end">
              <p class="card-title">{{ Auth::guard('member')->user()->experience_point }} Point</p>
            </div>
          </div>
        </div>
      @endif
    </div>
  </section>
@endsection
