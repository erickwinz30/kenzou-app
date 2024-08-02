<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Kasir</li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="index.html">
        <i class="bi bi-grid"></i>
        <span>Dashboard Kasir</span>
      </a>
    </li><!-- End Dashboard Kasir Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('transaksiBaru') ? '' : 'collapsed' }}" href="/transaksiBaru">
        <i class="bi bi-cart"></i>
        <span>Transaksi Baru</span>
      </a>
    </li><!-- End Transaksi Baru Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('list-layanan') ? '' : 'collapsed' }}" href="/list-layanan">
        <i class="bi bi-collection"></i>
        <span>Layanan</span>
      </a>
    </li><!-- End Layanan Page Nav -->

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="tables-general.html">
            <i class="bi bi-circle"></i><span>General Tables</span>
          </a>
        </li>
        <li>
          <a href="tables-data.html">
            <i class="bi bi-circle"></i><span>Data Tables</span>
          </a>
        </li>
      </ul>
    </li><!-- End Tables Nav -->

    @can('isAdmin')
      <li class="nav-heading">Admin</li>

      <li class="nav-item">
        <a class="nav-link {{ Request::is('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link {{ Request::is('transaksi/') || Request::is('transaksi/*') ? '' : 'collapsed' }}"
          href="/transaksi">
          <i class="bi bi-wallet2"></i>
          <span>Transaksi</span>
        </a>
      </li><!-- End Transaksi Page Nav -->

      <li class="nav-item">
        <a class="nav-link {{ Request::is('kasir') || Request::is('kasir/*') ? '' : 'collapsed' }}" href="/kasir">
          <i class="bi bi-person-gear"></i>
          <span>Kasir</span>
        </a>
      </li><!-- End Kasir Page Nav -->

      <li class="nav-item">
        <a class="nav-link {{ Request::is('layanan') || Request::is('layanan/*') ? '' : 'collapsed' }}" href="/layanan">
          <i class="bi bi-collection"></i>
          <span>Layanan</span>
        </a>
      </li><!-- End Layanan Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Pelanggan</span>
        </a>
      </li><!-- End Kasir Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-register.html">
          <i class="bi bi-bar-chart"></i>
          <span>Laporan</span>
        </a>
      </li><!-- End Laporan Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-contact.html">
          <i class="bi bi-envelope"></i>
          <span>Feedback</span>
        </a>
      </li><!-- End Feedback Page Nav -->


      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-error-404.html">
          <i class="bi bi-ticket-perforated"></i>
          <span>Voucher</span>
        </a>
      </li><!-- End Voucher Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-blank.html">
          <i class="bi bi-graph-up"></i>
          <span>Challenge</span>
        </a>
      </li><!-- End Challenge Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-blank.html">
          <i class="bi bi-person-badge"></i>
          <span>Badge</span>
        </a>
      </li><!-- End Badge Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-blank.html">
          <i class="bi bi-reception-4"></i>
          <span>Leaderboard</span>
        </a>
      </li><!-- End Leaderboard Page Nav -->
    @endcan

  </ul>

</aside><!-- End Sidebar-->
