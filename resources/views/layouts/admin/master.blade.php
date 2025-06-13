<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Caveat+Brush&family=Chewy&family=DynaPuff&family=Lora:ital,wght@0,400..700;1,400..700&family=Patrick+Hand&family=Vollkorn:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css')}}">
    @include('layouts.header')
    @livewireStyles
</head>

<style>
    * {
    font-size: 12px;
     font-family: "DynaPuff", system-ui;
    font-weight: 400;
    font-style: normal;
}

.divider:after,
.divider:before {
    content: "";
    flex: 1;
    height: 1px;
    background: #eee;
}
.h-custom {
    height: calc(100% - 73px);
}
@media (max-width: 450px) {
    .h-custom {
        height: 100%;
    }
}

.dynamic-select {
    display: block;
    overflow-y: auto;
    min-height: 38px; /* Sesuai dengan tinggi form-control */
    max-height: 150px; /* Batas tinggi maksimal */
    resize: vertical; /* Memungkinkan pengguna menyesuaikan ukuran */
}


.file-upload {
    background-color: #ffffff;
    width: 100%;
    margin: 0 auto;
    padding: 50px;
}

.download-template-btn {
    width: 100%;
    margin: 0;
    color: #fff;
    background: #1fb264;
    border: none;
    padding: 10px;
    border-radius: 4px;
    border-bottom: 4px solid #15824b;
    transition: all 0.2s ease;
    outline: none;
    text-transform: uppercase;
    font-weight: 700;
}

.download-template-btn:hover {
    background: #1aa059;
    color: #ffffff;
    transition: all 0.2s ease;
    cursor: pointer;
}

.download-template-btn:active {
    border: 0;
    transition: all 0.2s ease;
}

.file-upload-content {
    display: none;
    text-align: center;
}

.file-upload-input {
    position: absolute;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    outline: none;
    opacity: 0;
    cursor: pointer;
}

.image-upload-wrap {
    margin-top: 20px;
    border: 4px dashed #1fb264;
    position: relative;
}

.image-dropping,
.image-upload-wrap:hover {
    background-color: #1fb264;
    border: 4px dashed #ffffff;
}

.image-title-wrap {
    padding: 0 15px 15px 15px;
    color: #222;
}

.drag-text {
    text-align: center;
}

.drag-text h3 {
    font-weight: 100;
    text-transform: uppercase;
    color: #15824b;
    padding: 60px 0;
}

.file-upload-image {
    max-height: max-content;
    max-width: max-content;
    margin: auto;
    margin-top: 20px;
    margin-bottom: 20px;
    padding: 20px;
}

.remove-image {
    width: 200px;
    margin: 0;
    color: #fff;
    background: #cd4535;
    border: none;
    padding: 15px;
    border-radius: 4px;
    border-bottom: 4px solid #b02818;
    transition: all 0.2s ease;
    outline: none;
    text-transform: uppercase;
    font-weight: 700;
}

.upload-image {
    width: 200px;
    margin: 0;
    color: #fff;
    background: #0183a3;
    border: none;
    padding: 15px;
    border-radius: 4px;
    border-bottom: 4px solid #00566b;
    transition: all 0.2s ease;
    outline: none;
    text-transform: uppercase;
    font-weight: 700;
}

.remove-image:hover {
    background: #c13b2a;
    color: #ffffff;
    transition: all 0.2s ease;
    cursor: pointer;
}

.remove-image:active {
    border: 0;
    transition: all 0.2s ease;
}

.custom-control-label::before,
.custom-control-label::after {
    top: 0.8rem;
    width: 1.25rem;
    height: 1.25rem;
}

.bg-image {
    /* background-image: url("images/lgn.jpg"); */
    background-image: url("images/depan_sekolah_new.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh; /* Membuat background memenuhi seluruh layar */
    position: relative;
}

/* Overlay dengan opacity */
.bg-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7); /* Mengatur opacity agar background tidak terlalu gelap */
    z-index: 1;
}

.container-fluid {
    position: relative;
    z-index: 2; /* Menempatkan konten di atas overlay */
}

/* ANIMATION */
.small-box {
    transition: transform 0.1s ease-in-out;
}

.small-box:hover {
    cursor: pointer;
    transform: scale(1.04);
}

.box {
    transition: transform 0.3s ease-in-out;
}

.box:hover {
    transform: scale(0.95);
}

/* END ANIMATION */

.grid-container-profil {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-gap: 10px;
    padding: 5px;
}

/* Untuk seluruh elemen yang memiliki scrollbar */
*::-webkit-scrollbar {
    width: 2px; /* Mengatur lebar scrollbar */
    height: 6px; /* Mengatur tinggi scrollbar untuk horizontal */
}

/* Styling thumb (bagian yang bisa digeser) */
*::-webkit-scrollbar-thumb {
    background-color: orange; /* Warna scrollbar */
    border-radius: 0px; /* Membuat sudut lebih halus */
}

/* Styling track (jalur scrollbar) */
*::-webkit-scrollbar-track {
    background-color: #f0f0f0; /* Warna latar belakang track scrollbar */
    border-radius: 0px;
}


.custom-card-student {
    /* padding: 40px 80px; */
    background-color: #A8DADC;
    color: #000;
    border-radius: 30% 40% 30% 40% / 40% 30% 40% 30% ;
    border: 3px solid #1D3357;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(63, 5, 222, 0.1);
    margin: 10px
}

.custom-card-student:hover {
    cursor: pointer;
    opacity: 1;
    transform: rotate(-2deg) scale(1.05);
}

.dynapuff-regular {
    font-family: "DynaPuff", system-ui;
    font-optical-sizing: auto;
    font-weight: 400;
    font-style: normal;
    font-variation-settings:
    "wdth" 100;
}

.btn-menu-top {
    margin-top: 0px;
    padding: 0px;
    width: 72px;
    height: 32px;
    background-color: #ffde9e;
    color: #1D3557;
    clip-path: polygon(
    10% 0%, 20% 10%, 35% 5%, 50% 15%, 65% 5%, 80% 10%, 90% 0%, 
    100% 20%, 95% 35%, 100% 50%, 95% 65%, 100% 80%, 90% 100%, 
    75% 90%, 60% 95%, 50% 85%, 40% 95%, 25% 90%, 10% 100%, 
    0% 80%, 5% 65%, 0% 50%, 5% 35%, 0% 20%
    );
    border: 3px solid #ffde9e;
    overflow: hidden;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
}

.btn-menu-top:hover {
    transform: scale(1.2) rotate(-3deg);
    color: #e47600;
    animation: bounce 0.3s ease-in-out;
}

.hero-title {
    font-size: 1.2rem;
    color: #ff9000;
    letter-spacing: 1px;
    /* text-shadow: 2px 2px #ffcc00; */
}

.chewy-regular {
    font-family: "Chewy", system-ui;
    font-weight: 400;
    font-style: normal;
}

.shadow-soft {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1),
    0 2px 4px rgba(0, 0, 0, 0.08);
  border-radius: 12px;
}

.icon-assessment {
    background-image: url("images/assessment.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh; /* Membuat background memenuhi seluruh layar */
    position: relative;
}

/* Contoh bentuk radius non-standar (menggunakan clip-path atau border-radius custom) */
.swal2-popup.rounded-swal {
    padding: 15px;
    border-radius: 86% 64% 74% 76% / 76% 80% 80% 94%;
    background-color: #ffde9e;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    border: 3px solid #ffcc00;
}


.custom-swal-style {
    padding: 20px;
    background-color: #ffde9e !important;
    color: #000 !important;
    border-radius: 86% 64% 74% 76% / 76% 80% 80% 94% !important;
    border: 3px solid #ffcc00 !important;
    overflow: hidden !important;
    display: grid !important;
    align-items: center !important;
    justify-content: center !important;
    transition: transform 0.3s !important;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1) !important;
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
        const topScroll = document.getElementById('scroll-top');
        const bottomScroll = document.getElementById('scroll-bottom');

        topScroll.addEventListener('scroll', () => {
            bottomScroll.scrollLeft = topScroll.scrollLeft;
        });

        bottomScroll.addEventListener('scroll', () => {
            topScroll.scrollLeft = bottomScroll.scrollLeft;
        });
    </script>

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
