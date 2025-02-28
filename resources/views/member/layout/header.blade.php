<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center justify-content-between">

  <div class="d-flex align-items-center justify-content-between">
    <i class="bi bi-list toggle-sidebar-btn ps-0 pe-2 d-none d-lg-block"></i>
    <a href="{{ route('homepage') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('img/icons/icon-152.png') }}" alt="Kenzou Logo">
      <span>
        @if (Request::is('/'))
          Home
        @endif
      </span>
    </a>
  </div><!-- End Logo -->

  <div>
    <p class="m-0 me-3 fw-semibold" style="color: #012970">{{ Str::limit(Auth::guard('member')->user()->nama, 12) }}</p>
  </div>

</header><!-- End Header -->
