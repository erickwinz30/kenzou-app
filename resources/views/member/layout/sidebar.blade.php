<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link {{ Request::is('/') || Request::is('/*') ? '' : 'collapsed' }}" href="{{ route('homepage') }}">
        <i class="bi bi-house-door-fill me-0"></i>
        <span class="d-none d-lg-block ms-2">Home</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('voucher') || Request::is('voucher/*') ? '' : 'collapsed' }}"
        href="{{ route('voucher-index') }}">
        <i class="bi bi-gift me-0"></i>
        <span class="d-none d-lg-block ms-2">Voucher</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.html">
        <i class="bi bi-bar-chart me-0"></i>
        <span class="d-none d-lg-block ms-2">Leaderboard</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('account') || Request::is('account/*') ? '' : 'collapsed' }}"
        href="{{ route('account') }}">
        <i class="bi bi-three-dots me-0"></i>
        <span class="d-none d-lg-block ms-2">More</span>
      </a>
    </li><!-- End Dashboard Nav -->

  </ul>
</aside><!-- End Sidebar-->