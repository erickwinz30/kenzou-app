<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center justify-content-between">

  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('homepage') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('img/icons/icon-152.png') }}" alt="Kenzou Logo">
      <span class="d-none d-lg-block">Kenzou</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <div>
    @if (Request::is('dashboard/transaksi'))
      <div class="me-4">
        <a href="/dashboard/transaksiBaru" class="btn btn-success">
          <i class="bi bi-plus me-1"></i>Transaksi
        </a>
      </div>
    @elseif(Request::is('dashboard/layanan'))
      <div class="me-4">
        <a href="/dashboard/layanan/create" type="button" class="btn btn-success d-inline">
          <i class="bi bi-plus" style="margin-right: 2px;"></i>Layanan
        </a>
        <a href="{{ route('layanan.history') }}" type="button" class="btn btn-info d-inline">
          <i class="bi bi-clock-history" style="margin-right: 2px;"></i>History
        </a>
      </div>
    @elseif (Request::is('dashboard/kasir'))
      <div class="me-4">
        <button type="button" class="btn btn-success d-inline" data-bs-toggle='modal' data-bs-target='#inputModal'>
          <i class="bi bi-plus" style="margin-right: 2px;"></i>Kasir
        </button>
      </div>
    @endif
  </div>

  {{-- <div class="search-bar">
    <form class="search-form d-flex align-items-center" method="POST" action="#">
      <input type="text" name="query" placeholder="Search" title="Enter search keyword">
      <button type="submit" title="Search"><i class="bi bi-search"></i></button>
    </form>
  </div><!-- End Search Bar --> --}}

  {{-- <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
      </li><!-- End Search Icon-->

    </ul>
  </nav><!-- End Icons Navigation --> --}}

</header><!-- End Header -->
