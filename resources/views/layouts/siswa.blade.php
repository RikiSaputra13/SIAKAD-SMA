<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | SIAKAD</title>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    @include('siswa.partials.navbar')
    @include('siswa.partials.sidebar')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>@yield('title')</h1>
            </div>
        </section>

        <section class="content">
            @yield('content')
        </section>
    </div>

    @include('siswa.partials.footer')
</div>

<!-- JS -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<!-- Toast Notifikasi Jadwal -->
<script>
@isset($jadwalHariIni)
    @foreach($jadwalHariIni as $j)
        $(document).Toasts('create', {
            title: 'Jadwal Hari Ini',
            body: '{{ $j->mapel }} ({{ $j->jam_mulai }} - {{ $j->jam_selesai }})',
            autohide: true,
            delay: 5000,
            class: 'bg-info'
        });
    @endforeach
@endisset
</script>

@yield('scripts')
</body>
</html>
