<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="../dashboard/index.html" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        <img src="../assets/images/logo-dark.svg" class="img-fluid logo-lg" alt="logo">
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item active">
          <a href="{{ route('dashboard.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>



        <li class="pc-item pc-caption">
          <label>Side Panel</label>
          <i class="ti ti-brand-chrome"></i>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-stack-2"></i></span><span class="pc-mtext">Master Data</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('customer.index') }}">Customer</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('service.index') }}">Jenis Service</a></li>
            <li class="pc-item pc-hasmenu">
              <a href="#!" class="pc-link">User<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                <li class="pc-item"><a class="pc-link" href="{{ route('user.index') }}">Users</a></li>
                <li class="pc-item"><a class="pc-link" href="{{ route('level.index') }}">Levels</a></li>
              </ul>
            </li>
          </ul>
        </li>

        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-receipt"></i></span><span class="pc-mtext">Transaksi</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('pickup.index') }}">Pickup</a></li>
        </a>
        </li>
          </ul>
        </li>
      </ul>

    </div>
  </div>
</nav>
