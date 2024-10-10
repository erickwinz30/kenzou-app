@extends('member.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Informasi Akun</h1>
  </div><!-- End Page Title -->

  <section class="section mt-4">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">
        <div class="d-flex justify-content-center mb-4">
          <div class="d-block">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h1 class="fs-1 fw-bold">{{ $information->nama }}</h1>
              <h2 class="fs-4">Gold</h2>
            </div>
            <div class="d-flex justify-content-center">
              <h2 class="fs-4 text-center"><strong>Exp: </strong> {{ $information->experience_point }} Point</h2>
              <p class="mx-4">|</p>
              <h2 class="fs-4 text-center"><strong>Redeemable: </strong> {{ $information->redeemable_point }} Point</h2>
            </div>
            <div class="mt-3">
              <h2 class="fs-4 text-center"><strong>Referral: </strong> {{ $information->referral_code }}</h2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="card">
            <div class="card-body p-0">
              <div class="d-flex flex-column">
                <a href="{{ route('account-edit') }}" class="card-text fs-5 text-decoration-none text-reset mt-3">Edit
                  Profil</a>
                <hr class="my-2">
                <a href="{{ route('transaction-history') }}"
                  class="card-text fs-5 text-decoration-none text-reset">Riwayat Transaksi</a>
                <hr class="my-2">
                <a href="{{ route('point-history') }}" class="card-text fs-5 text-decoration-none text-reset">Riwayat
                  Point</a>
                <hr class="my-2">
                <a href="#" class="card-text fs-5 text-decoration-none text-reset mb-3">Feedback ke Kenzou</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
