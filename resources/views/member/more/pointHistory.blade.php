@extends('member.layout.main')

@section('container')
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Riwayat Point</h1>
    <a href="{{ route('account') }}" class="btn btn-primary"
      style="background-color: #012970; border-color:#012970">Kembali</a>
  </div><!-- End Page Title -->

  <section class="section my-4">
    <div class="col-12">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
        @foreach ($informations as $information)
          <div class="col">
            <div class="card shadow" style="border-radius: 15px">
              @php
                $formattedDate = \Carbon\Carbon::parse($information->date)->translatedFormat('j F Y, H:i');
              @endphp
              <div class="card-title p-3 m-0">{{ $formattedDate }}</div>
              <div class="d-flex justify-content-between align-items-center p-3">
                <p class="fw-semibold m-0"><strong style="color: #012970">Dari: </strong>{{ $information->status }}</p>
                <p class="fw-semibold m-0" style="color: {{ $information->is_increase ? 'green' : 'red' }}"><strong
                    style="color: #012970">Point: </strong>
                  {{ $information->is_increase ? '+' : '-' }}{{ $information->point }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection
