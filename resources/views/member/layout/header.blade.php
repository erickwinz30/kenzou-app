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

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <span class="dropdown-toggle ps-2">{{ Str::limit(Auth::guard('member')->user()->nama, 12) }}</span>
        </a><!-- End Profile Name -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ Auth::guard('member')->user()->nama }}</h6>
            <span>Gold</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
              <i class="bi bi-gear"></i>
              <span>Account Settings</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
              <i class="bi bi-question-circle"></i>
              <span>Need Help?</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/logout">
              <i class="bi bi-box-arrow-right"></i>
              <span>Log Out</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->


    </ul>
  </nav><!-- End Icons Navigation -->

</header><!-- End Header -->
