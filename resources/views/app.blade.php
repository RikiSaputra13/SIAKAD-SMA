<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIAKAD - SMA Pangeran Jayakarta</title>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

  <!-- Custom Sidebar Style (letakkan setelah adminlte.min.css) -->
  <style>
    /* Sidebar background biru */
    #main-sidebar {
        background: linear-gradient(180deg, #007bff, #0056b3) !important;
        color: #fff !important;
    }

    /* Logo dan link warna putih */
    #main-sidebar .brand-link,
    #main-sidebar .nav-link {
        color: #fff !important;
    }

    /* Hover menu */
    #main-sidebar .nav-link:hover {
        background-color: #004494 !important;
        color: #fff !important;
    }

    /* Menu aktif */
    #main-sidebar .nav-link.active {
        background-color: #003366 !important;
        color: #fff !important;
    }

    /* Border bawah logo */
    #main-sidebar .brand-link {
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    /* Garis pemisah */
    #main-sidebar .user-panel,
    #main-sidebar .nav-sidebar > .nav-item {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#" role="button" id="toggle-sidebar">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('/') }}" class="nav-link">Home</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
        </a>

        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="#"
             onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar -->
  <aside id="main-sidebar" class="main-sidebar elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/dashboard') }}" class="brand-link">
      <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">SIAKAD PJ</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- User panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}"
               class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="{{ url('/dashboard') }}" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/jadwal') }}" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Jadwal</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/absensi') }}" class="nav-link">
              <i class="nav-icon fas fa-user-check"></i>
              <p>Absensi</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h1 class="m-0">@yield('title', 'Dashboard')</h1>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer text-center">
    <strong>&copy; {{ date('Y') }} SIAKAD SMA Pangeran Jayakarta</strong>
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
