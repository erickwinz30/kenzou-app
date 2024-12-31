@extends('member.layout.main')

@section('container')
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1>Feedback</h1>
    <a href="{{ route('account') }}" class="btn btn-primary"
      style="background-color: #012970; border-color:#012970">Kembali</a>
  </div><!-- End Page Title -->

  @if (session()->has('success'))
    <x-alert-success :message="session('success')" />
  @endif

  @if (session()->has('error'))
    <x-alert-error :message="session('error')" />
  @endif

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Berikan feedback anda terhadap pelayanan, pengalaman anda, dan lainnya agar kami dapat
              meningkatkan pengalaman anda selama melakukan pencucian</h5>
            <form action="/account/feedback" method="POST" id="addForm" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject"
                  name="subject" placeholder="Masukkan subjectnya.." required autofocus>
                @error('description')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <input type="hidden" name="member_email" id="member_email"
                value="{{ Auth::guard('member')->user()->email }}">
              <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                  rows="3" placeholder="Silahkan diisi disini.."></textarea>
                @error('description')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="modal-footer">
                <a href="/account" class="btn btn-secondary me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Tambah</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
