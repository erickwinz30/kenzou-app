@extends('dashboard.layout.main')

@section('container')
  <div class="pagetitle">
    <h1>Feedback</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="/dashboard/feedback">Feedback</a></li>
        <li class="breadcrumb-item active"><a href="/dashboard/feedback/{{ $feedback->id }}">Detail</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3 fs-4">Feedback dari {{ $feedback->member->nama }}</h5>
            <div class="mb-4">
              <h5 class="mb-0">Informasi Member</h5>
              <p>{{ $feedback->member->nama }} ({{ $feedback->member->email }})</p>
            </div>
            <div class="mb-4">
              <h5 class="mb-0">Subject Feedback</h5>
              <p>{{ $feedback->subject }}</p>
            </div>
            <div class="mb-4">
              <h5 class="mb-0">Feedback yang diberikan</h5>
              <p>{{ $feedback->description }}</p>
            </div>
            <a href="/dashboard/feedback" class="btn btn-info">Kembali</a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
