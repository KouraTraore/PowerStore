<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>@yield('title', 'POWERSTORE - Dashboard')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon_io/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon_io/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon_io/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('images/favicon_io/site.webmanifest') }}">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tabler Icons -->
  <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  @stack('styles')
</head>

<body>
  <div id="overlay" class="overlay"></div>

  <!-- TOPBAR -->
  <nav id="topbar" class="navbar bg-white border-bottom fixed-top topbar px-3">
    <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm">
      <i class="ti ti-layout-sidebar-left-expand"></i>
    </button>

    <!-- MOBILE -->
    <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2">
      <i class="ti ti-layout-sidebar-left-expand"></i>
    </button>

    <div>
      <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
        <!-- Bell icon -->
        <li>
          <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown"
            aria-expanded="false" href="#" role="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
              class="icon icon-tabler icons-tabler-outline icon-tabler-bell">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
              <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
            </svg>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2">
              2
              <span class="visually-hidden">unread messages</span>
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
            <ul class="list-unstyled p-0 m-0">
              <li class="p-3 border-bottom">
                <div class="d-flex gap-3">
                  <img src="{{ asset('images/avatar/avatar-1.jpg') }}" alt="" class="avatar avatar-sm rounded-circle" />
                  <div class="flex-grow-1 small">
                    <p class="mb-0">New order received</p>
                    <p class="mb-1">Order #12345 has been placed</p>
                    <div class="text-secondary">5 minutes ago</div>
                  </div>
                </div>
              </li>
              <li class="p-3 border-bottom">
                <div class="d-flex gap-3">
                  <img src="{{ asset('images/avatar/avatar-4.jpg') }}" alt="" class="avatar avatar-sm rounded-circle" />
                  <div class="flex-grow-1 small">
                    <p class="mb-0">New user registered</p>
                    <p class="mb-1">User @john_doe has signed up</p>
                    <div class="text-secondary">30 minutes ago</div>
                  </div>
                </div>
              </li>
              <li class="p-3 border-bottom">
                <div class="d-flex gap-3">
                  <img src="{{ asset('images/avatar/avatar-2.jpg') }}" alt="" class="avatar avatar-sm rounded-circle" />
                  <div class="flex-grow-1 small">
                    <p class="mb-0">Payment confirmed</p>
                    <p class="mb-1">Payment of $299 has been received</p>
                    <div class="text-secondary">1 hour ago</div>
                  </div>
                </div>
              </li>
              <li class="px-4 py-3 text-center">
                <a href="#" class="text-primary">View all notifications</a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Dropdown user -->
        <li class="ms-3 dropdown">
          <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="avatar-circle">
            {{ strtoupper(substr(auth()->user()->prenom ?? 'U', 0, 1) . substr(auth()->user()->nom ?? 'A', 0, 1)) }}
          </a>
          <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 200px;">
            <div>
              <div class="d-flex gap-3 align-items-center border-dashed border-bottom px-3 py-3">
                <div class="avatar avatar-md rounded-circle avatar-circle-lg">
                  {{ strtoupper(substr(auth()->user()->prenom ?? 'U', 0, 1) . substr(auth()->user()->nom ?? 'A', 0, 1)) }}
                </div>
                <div>
                  <h4 class="mb-0 small">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h4>
                  <p class="mb-0 small text-secondary">@{{ auth()->user()->username }}</p>
                </div>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <!-- SIDEBAR -->
  <aside id="sidebar" class="sidebar">
    <div class="logo-area">
      <a href="{{ route('admin.index') }}" class="d-inline-flex">
        <img src="{{ asset('images/logo_site/logo.svg.png') }}" alt="" width="100%">
      </a>
    </div>
    <ul class="nav flex-column">
      <li class="px-4 py-2"><small class="nav-text">Principaux</small></li>
      <li>
        <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') }}">
          <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
      </li>
      <li>
        <a class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" href="{{ route('admin.clients.index') }}">
          <i class="ti ti-users"></i><span class="nav-text">Clients</span>
        </a>
      </li>
      <li>
        <a class="nav-link {{ request()->routeIs('admin.category') ? 'active' : '' }}" href="{{ route('admin.category') }}">
          <i class="ti ti-box-seam"></i><span class="nav-text">Catégories</span>
        </a>
      </li>
      <li>
        <a class="nav-link {{ request()->routeIs('admin.product.index') ? 'active' : '' }}" href="{{ route('admin.product.index') }}">
          <i class="ti ti-plus"></i><span class="nav-text">Produits</span>
        </a>
      </li>

      <li>
        <a class="nav-link {{ request()->routeIs('admin.commandes.*') ? 'active' : '' }}" href="{{ route('admin.commandes.index') }}">
          <i class="ti ti-shopping-cart"></i><span class="nav-text">Commandes</span>
        </a>
      </li>

      <li>
        <a class="nav-link {{ request()->routeIs('admin.factures.*') ? 'active' : '' }}" href="{{ route('admin.factures.index') }}">
          <i class="ti ti-receipt"></i><span class="nav-text">Factures</span>
        </a>
      </li>

      <li class="px-4 pt-4 pb-2"><small class="nav-text">Compte</small></li>
      @auth
        <li>
          <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="ti ti-logout"></i><span class="nav-text">Déconnexion</span>
          </a>
        </li>
      @else
        <li><a class="nav-link" href="{{ route('login') }}"><i class="ti ti-logout"></i><span class="nav-text">Se connecter</span></a></li>
      @endauth
    </ul>
  </aside>

  <!-- TOAST CONTAINER -->
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
      @if(session('success'))
          <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
              <div class="d-flex">
                  <div class="toast-body">
                      <i class="ti ti-check-circle me-2"></i> {{ session('success') }}
                  </div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
              </div>
          </div>
      @endif

      @if(session('error'))
          <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
              <div class="d-flex">
                  <div class="toast-body">
                      <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                  </div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
              </div>
          </div>
      @endif

      @if($errors->any())
          <div class="toast align-items-center text-white bg-warning border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
              <div class="d-flex">
                  <div class="toast-body">
                      <i class="ti ti-alert-triangle me-2"></i>
                      @foreach($errors->all() as $error)
                          {{ $error }}<br>
                      @endforeach
                  </div>
                  <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
              </div>
          </div>
      @endif
  </div>

  <!-- MAIN CONTENT -->
  <main id="content" class="content py-10">
    <div class="container-fluid">
      @yield('content')

      <div class="row">
        <div class="col-12">
          <footer class="text-center py-2 mt-6 text-secondary">
            <p class="mb-0">Copyright © 2026 POWERSTORE. Developed by <a href="https://codescandy.com/" target="_blank" class="text-primary">CodesCandy</a> • Distributed by <a href="https://themewagon.com/" target="_blank" class="text-primary">ThemeWagon</a></p>
          </footer>
        </div>
      </div>
    </div>
  </main>

  <!-- Formulaire de déconnexion caché -->
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/main.js') }}" type="module"></script>

  <!-- Initialisation des toasts -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var toastElList = [].slice.call(document.querySelectorAll('.toast'));
      toastElList.forEach(function (toastEl) {
        new bootstrap.Toast(toastEl, {
          autohide: true,
          delay: 5000
        }).show();
      });
    });
  </script>

  @stack('scripts')
</body>

</html>
