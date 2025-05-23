<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.header')
    @livewireStyles
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper pr-2">
        @if (session('preloader'))
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="{{ asset('images') }}/logo-school.png" alt="SchoolLogo" height="120"
                    width="290">
            </div>
        @endif

        @include('layouts.admin.navbar')
        @include('layouts.admin.sidebar')

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6 d-flex">
                            <i>
                               <img loading="lazy"
                                src="{{ asset('images/' . ucwords(optional(session('page'))->page ?? 'Customer-service') . '.png') }}"
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
    <!-- /.content-wrapper -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts
</body>

</html>
