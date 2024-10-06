@extends('member.layout.main')

@section('container')
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Riwayat Point</h1>
    <a href="{{ route('account') }}" class="btn btn-primary"
      style="background-color: #012970; border-color:#012970">Kembali</a>
  </div><!-- End Page Title -->

  <section class="section mt-4">
    <div class="col-12">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        @foreach ($informations as $information)
          <div class="col">
            <div class="card shadow" style="border-radius: 15px">
              <div class="card-title p-3 m-0">{{ $information->status }}</div>
              <div class="d-flex justify-content-between align-items-center p-3">
                <div class="card-text"><strong style="color: #012970">Dari: </strong> {{ $information->status }}</div>
                <div class="card-text"><strong style="color: #012970">Point: </strong> {{ $information->point }}</div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection
