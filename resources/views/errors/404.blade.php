@if (Auth::guard('member')->check())
  @extends('member.layout.main')
@else
  @extends('dashboard.layout.main')
@endif

@section('container')
  <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
    <h1>404</h1>
    <h2>Anda tidak memiliki akses pada halaman ini</h2>
    <a class="btn" href="{{ route('homepage') }}">Back to home</a>
    <img src="assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
    </div>
  </section>
@endsection
