<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link {{ Request::is('/') || Request::is('/*') ? '' : 'collapsed' }}" href="{{ route('homepage') }}">
        <i class="bi bi-house-door-fill"></i>
        <span>Home</span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="index.html">
        <i class="bi bi-gift"></i>
        <span>Voucher</span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="index.html">
        <i class="bi bi-house-door-fill"></i>
        <span>Home</span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="index.html">
        <i class="bi bi-bar-chart"></i>
        <span>Leaderboard</span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="index.html">
        <i class="bi bi-three-dots"></i>
        <span>More</span>
      </a>
    </li><!-- End Dashboard Nav -->
  </ul>

</aside><!-- End Sidebar-->
