<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <div class="accordion" id="accordionExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
          aria-expanded="false" aria-controls="collapseOne">
          <strong>{{ auth()->user()->nama }}</strong>
          <span class="fs-6 ms-1">(
            @if (Auth::check() && Auth::user()->is_admin)
            Admin
            @else
            Kasir
            @endif
            )
          </span>
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
        data-bs-parent="#accordionExample">
        <div class="accordion-body">
          <form action="/dashboard/logout" method="POST" id="logout-form" style="display: :none;">
            @csrf
          </form>
          <a class="dropdown-item d-flex align-items-center" href="#"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right me-2"></i>
            <span>Log Out</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <ul class="sidebar-nav" id="sidebar-nav">
    @can('notAdmin')
    <li class="nav-heading">Kasir</li>

    {{-- <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard') ? '' : 'collapsed' }}" href="/dashboard">
        <i class="bi bi-grid"></i>
        <span>Dashboard Kasir</span>
      </a>
    </li><!-- End Dashboard Kasir Nav --> --}}

    <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard/transaksiBaru') ? '' : 'collapsed' }}"
        href="/dashboard/transaksiBaru">
        <i class="bi bi-cart"></i>
        <span>Transaksi Baru</span>
      </a>
    </li><!-- End Transaksi Baru Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard/list-layanan') ? '' : 'collapsed' }}" href="/dashboard/list-layanan">
        <i class="bi bi-collection"></i>
        <span>Layanan</span>
      </a>
    </li><!-- End Layanan Page Nav -->
    @endcan

    @can('isAdmin')
    <li class="nav-heading">Admin</li>

    <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard/admin') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard/transaksi') || Request::is('dashboard/transaksi/*') ? '' : 'collapsed' }}"
        href="/dashboard/transaksi">
        <i class="bi bi-wallet2"></i>
        <span>Transaksi</span>
      </a>
    </li><!-- End Transaksi Page Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard/layanan') || Request::is('dashboard/layanan/*') || Request::is('dashboard/layananLog') ? '' : 'collapsed' }}"
        href="/dashboard/layanan">
        <i class="bi bi-collection"></i>
        <span>Layanan</span>
      </a>
    </li><!-- End Layanan Page Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard/pelanggan') || Request::is('dashboard/pelanggan/*') ? '' : 'collapsed' }}"
        href="/dashboard/pelanggan">
        <i class="bi bi-person"></i>
        <span>Pelanggan</span>
      </a>
    </li><!-- End Kasir Page Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard/voucher') || Request::is('dashboard/voucher/*') || Request::is('dashboard/challenge') || Request::is('dashboard/challenge/*') || Request::is('dashboard/badge') || Request::is('dashboard/badge/*') ? '' : 'collapsed' }}"
        data-bs-target="#tables-gamification" data-bs-toggle="collapse" href="#">
        <i class="bi bi-controller"></i><span>Gamifikasi</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="tables-gamification"
        class="nav-content collapse {{ Request::is('dashboard/voucher') || Request::is('dashboard/voucher/*') || Request::is('dashboard/challenge') || Request::is('dashboard/challenge/*') || Request::is('dashboard/badge') || Request::is('dashboard/badge/*') ? 'show' : '' }}"
        data-bs-parent="#sidebar-nav">

        <li>
          <a class="{{ Request::is('dashboard/voucher') || Request::is('dashboard/voucher/*') ? 'active' : '' }}"
            href="/dashboard/voucher">
            <i class="bi bi-circle"></i>
            <span>Voucher</span>
          </a>
        </li><!-- End Voucher Page Nav -->

        <li>
          <a class="{{ Request::is('dashboard/challenge') || Request::is('dashboard/challenge/*') ? 'active' : '' }}"
            href="/dashboard/challenge">
            <i class="bi bi-circle"></i>
            <span>Challenge</span>
          </a>
        </li><!-- End Challenge Page Nav -->

        <li>
          <a class="{{ Request::is('dashboard/badge') || Request::is('dashboard/badge/*') ? 'active' : '' }}"
            href="/dashboard/badge">
            <i class="bi bi-circle"></i>
            <span>Badge</span>
          </a>
        </li><!-- End Badge Page Nav -->
      </ul>
    </li><!-- End Tables Nav -->

    <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard/kasir') || Request::is('dashboard/kasir/*') || Request::is('dashboard/feedback') || Request::is('dashboard/feedback/*') ? '' : 'collapsed' }}"
        data-bs-target="#tables-more" data-bs-toggle="collapse" href="#">
        <i class="bi bi-three-dots"></i><span>Lainnya</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="tables-more"
        class="nav-content collapse {{ Request::is('dashboard/kasir') || Request::is('dashboard/kasir/*') || Request::is('dashboard/feedback') || Request::is('dashboard/feedback/*') ? 'show' : '' }}"
        data-bs-parent="#sidebar-nav">

        <li>
          <a class="nav-link {{ Request::is('dashboard/kasir') || Request::is('dashboard/kasir/*') ? 'active' : '' }}"
            href="/dashboard/kasir">
            <i class="bi bi-circle"></i>
            <span>Kasir</span>
          </a>
        </li><!-- End Kasir Page Nav -->

        <li>
          <a class="{{ Request::is('dashboard/feedback') || Request::is('dashboard/feedback/*') ? 'active' : '' }}"
            href="/dashboard/feedback">
            <i class="bi bi-circle"></i>
            <span>Feedback</span>
          </a>
        </li><!-- End Feedback Page Nav -->
      </ul>
    </li><!-- End Tables Nav -->


    {{-- <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard/badge-leaderboard') ? '' : 'collapsed' }}"
        href="/dashboard/badge-leaderboard">
        <i class="bi bi-reception-4"></i>
        <span>Leaderboard</span>
      </a>
    </li><!-- End Leaderboard Page Nav --> --}}
    @endcan

  </ul>

</aside><!-- End Sidebar-->