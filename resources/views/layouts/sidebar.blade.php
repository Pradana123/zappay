<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ url('/') }}" target="_blank" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/admin/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/admin/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ url('/') }}" target="_blank" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/admin/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ !$config ? '' : $config->logo_header }}" alt="ZAPPAY" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="assets/admin/images/users/avatar-1.jpg" alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">{{ Str::title(Auth()->user()->username) }}</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                            class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                            class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome {{ Str::title(Auth()->user()->username) }}!</h6>
            <a class="dropdown-item" href="#"><i
                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Profile</span></a>
            {{-- <a class="dropdown-item" href="apps-chat.html"><i
                    class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Messages</span></a>
            <a class="dropdown-item" href="apps-tasks-kanban.html"><i
                    class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Taskboard</span></a>
            <a class="dropdown-item" href="pages-faqs.html"><i
                    class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Help</span></a> --}}
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href=""><i
                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance :
                    <b>Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</b></span></a>
            <a class="dropdown-item" href="{{ url('setting/web') }}"><span
                    class="badge bg-success-subtle text-success mt-1 float-end">New</span><i
                    class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Settings</span></a>
            {{-- <a class="dropdown-item" href="auth-lockscreen-basic.html"><i
                    class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Lock screen</span></a> --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class='dropdown-item'>
                    <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                    <span class="align-middle" data-key="t-logout">Logout</span>
                </button>
            </form>
        </div>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

               <li class="menu-title"><span data-key="t-menu">Menu</span></li>
               <!-- Dashboard -->
               <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::is('dashboard') ? 'active' : '' }}" 
                    href="{{ route('dashboard') }}">
                        <i class="ri-dashboard-2-line"></i> 
                        <span data-key="t-widgets">Dashboards</span>
                    </a>
                </li>

                <!-- Pesanan -->
                <li class="nav-item">
  <a class="nav-link {{ Request::is('pesanan*') ? 'active' : '' }}" href="{{ route('pesanan') }}">
    <i class="ri-shopping-cart-2-line"></i>
    <span data-key="t-dashboards">Semua Pesanan</span>
  </a>
</li>

                <!-- Member -->
                <li class="nav-item">
  <a class="nav-link menu-link {{ Request::is('member*') ? 'active' : '' }}" href="{{ url('member') }}">
    <i class="ri-group-line"></i>
    <span data-key="t-member">Kelola Member</span>
  </a>
</li>
                <li class="menu-title"><span data-key="t-menu">Settings</span></li>
                
                <!-- Konfigurasi -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::is('berita*') || Request::is('method*') || Request::is('setting/web*') || Request::is('setting/api*') ? 'active' : '' }}" 
                    href="#sidebarKonfigurasi" data-bs-toggle="collapse" role="button" 
                    aria-expanded="{{ Request::is('berita*') || Request::is('method*') || Request::is('setting/web*') || Request::is('setting/api*') ? 'active' : '' }}" 
                    aria-controls="sidebarKonfigurasi">
                        <i class="ri-settings-line"></i>
                        <span data-key="t-config">Konfigurasi</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Request::is('berita*') || Request::is('method*') || Request::is('setting/web*') || Request::is('setting/api*') ? 'show' : '' }}" 
                        id="sidebarKonfigurasi">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('berita') }}" 
                                class="nav-link {{ Request::is('berita*') ? 'active' : '' }}">Banner/Popup</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('method') }}" 
                                class="nav-link {{ Request::is('method*') ? 'active' : '' }}">Metode Pembayaran</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('setting/web') }}" 
                                class="nav-link {{ Request::is('setting/web*') ? 'active' : '' }}">Website</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('setting/api') }}" 
                                class="nav-link {{ Request::is('setting/api*') ? 'active' : '' }}">API</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title"><span data-key="t-menu">Products</span></li>

                <!-- Product -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::is('kategori*') || Request::is('layanan*') || Request::is('paket*') ? 'active' : '' }}" 
                    href="#sidebarProducts" data-bs-toggle="collapse" role="button" 
                    aria-expanded="{{ Request::is('kategori*') || Request::is('layanan*') || Request::is('paket*') ? 'active' : '' }}" 
                    aria-controls="sidebarProducts">
                    <i class="ri-box-3-line"></i>
                        <span data-key="t-product">Produk</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Request::is('kategori*') || Request::is('layanan*') || Request::is('paket*') ? 'show' : '' }}" 
                        id="sidebarProducts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('kategori') }}" 
                                class="nav-link {{Request::is('kategori*') ? 'active' : '' }}">Kategori</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('layanan') }}" 
                                class="nav-link {{ Request::is('layanan*') ? 'active' : '' }}">Layanan</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('paket') }}" 
                                class="nav-link {{ Request::is('paket*') ? 'active' : '' }}">Paket Layanan</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Rating -->
                <li class="nav-item">
  <a class="nav-link {{ Request::is('rating-customer*') ? 'active' : '' }}" href="{{ route('rating-customer') }}">
    <i class='bx bxs-star' ></i>
    <span data-key="t-rating">Kelola Rating</span>
  </a>
</li>
                <!-- Voucher -->
                <li class="nav-item">
  <a class="nav-link {{ Request::is('voucher*') ? 'active' : '' }}" href="{{ url('voucher') }}">
    <i class='bx bxs-discount'></i>
    <span data-key="t-voucher">Buat Voucher</span>
  </a>
</li>
                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
