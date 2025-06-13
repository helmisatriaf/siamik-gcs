<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Caveat+Brush&family=Chewy&family=DynaPuff&family=Lora:ital,wght@0,400..700;1,400..700&family=Patrick+Hand&family=Vollkorn:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css')}}">
    @include('layouts.header')
    @livewireStyles
</head>

<style>
    body {
        font-family: "DynaPuff", system-ui !important;
        font-weight: 400;
        font-style: normal;
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
{{-- <body class="layout-fixed layout-navbar-fixed layout-footer-fixed"> --}}
    <div class="wrapper">
        @if (session('preloader'))
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="{{ asset('images') }}/logo-school.png" alt="SchoolLogo" height="120"
                    width="290">
            </div>
        @endif

        @include('layouts.admin.navbar')
        @include('layouts.admin.sidebar')

        {{-- @if (session('role') == 'student' || session('role') == 'parent') --}}
        <div class="content-wrapper" style="background-color: #fff3c0;">
        {{-- @else
        <div class="content-wrapper">
        @endif --}}
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6 d-flex">
                            <i>
                               <img loading="lazy"
                                src="{{ asset('images/' . strtolower(optional(session('page'))->page ?? 'Customer-service') . '.png') }}"
                                class="" alt="User Image"
                                style="width: 42px; height: 42px; object-fit: cover;">
                            </i>
                            <h1 class="m-0">|
                                {{ optional(session('page'))->child ? ucwords(str_replace('-', ' ', session('page')->child)) : 'Customer Service' }}
                            </h1>
                        </div>
                    {{--    <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active">{{session('page') && session('page')->child? ucwords(session('page')->child) : ''}}</li>
                                </ol>
                            </div> --}}
                    </div>
                </div>
            </div>

            <section class="content">
                @yield('content')
            </section>

            @if (session('role') == 'parent' || session('role') == 'student')
                <x-tutorials.page-tutorial :page-name="'form-tutorial'" />
            @endif

        </div>

        {{-- @include('components.tutorials.page-tutorial') --}}

        @include('layouts.footer')

        <!-- /.content -->
    </div>

    {{-- <audio id="clickSound" src="{{ asset('music/click.mp3') }}" style="display: none;"></audio> --}}
    <!-- /.content-wrapper -->
    <script>
        let clickSound = document.getElementById("clickSound");

        // Event listener untuk semua klik di halaman
        document.addEventListener("click", function () {
            clickSound.volume = 1;
            clickSound.currentTime = 0; // Reset audio ke awal
            clickSound.play(); // Putar suara
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts
</body>

</html>
