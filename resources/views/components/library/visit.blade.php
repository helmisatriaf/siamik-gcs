<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Library ・ Great Crystal School</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('/style.css') }}">
  <link rel="stylesheet" href="{{ asset('template') }}/dist/css/adminlte.min.css">
  <link rel="icon" href="{{ asset('images/greta-face.png') }}" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Caveat+Brush&family=Caveat:wght@400..700&family=Chewy&family=DynaPuff&display=swap" rel="stylesheet">

  <style>
    body {
      background-color: #f8f5f0;
      font-family: 'Noto Sans JP', sans-serif;
    }

    .card-header {
      background: linear-gradient(90deg, #4e73df, #36b9cc);
    }

    .nav-link {
      color: #4a2e1f !important;
    }

    .card-body ul li {
      font-size: 1.05rem;
      padding-left: 0.5rem;
    }

    .custom-dropdown-menu {
      opacity: 0;
      visibility: hidden;
      transform: translateY(10px);
    }

    .custom-dropdown:hover .custom-dropdown-menu {
      display: block;
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    
    /* Dropdown hover behavior */
    .custom-dropdown:hover .custom-dropdown-menu {
      display: block;
    }

    .custom-dropdown {
      position: relative;
    }

    /* Ubah ini */
    .custom-dropdown-menu {
      display: none;
      position: absolute;
      top: 100%; /* langsung di bawah tombol */
      left: 0;
      background-color: #fff;
      border: 2px dashed #000;
      z-index: 999;
      width: 240px;
      padding: 1px 0;
      box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
    }

    .custom-dropdown-menu li {
      padding: 5px;
    }

    .custom-dropdown-menu li a {
      font-weight: bold;
      color: #000000;
      text-decoration: none;
    }

    .custom-dropdown-menu li a:hover {
      background-color: #ff9000;
    }

    .custom-dropdown-menu li:hover {
      background-color: #ff9000;
    }

    /* Fullscreen mobile nav */
    .mobile-nav-overlay {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 100%;
      background-color: white;
      z-index: 9999;
      display: none;
      flex-direction: column;
      justify-content: flex-start;
      background-image: url(asset('images/school.webp')); /* kalau ingin motif */
      background-size: cover;
    }

    /* Show overlay */
    .mobile-nav-overlay.show {
      display: flex;
    }

    /* Menu item styling */
    .mobile-nav-overlay .nav-link {
      font-size: 1.2rem;
      font-weight: bold;
      color: #000;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .yellow-bg {
      background-color: #fff3c0;
    }

    .orange-bg {
      background-color: #ffde9e;
    }

    .peach-bg {
      background-color: #ffe8d6;
    }

    .visit-btn {
      position: relative;
      display: inline-block;
      overflow: hidden;
      border-radius: 4px;
      transition: transform 0.3s ease;
    }

    .visit-btn::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #ff9000;
      transform: rotate(-2deg) scale(0.95);
      transform-origin: center;
      z-index: 1;
      opacity: 0;
      transition: all 0.3s ease-in-out;
      border-radius: 4px;
    }

    /* Hover effect */
    .visit-btn:hover::before {
      opacity: 1;
      transform: rotate(-2deg) scale(1.05);
    }

    .visit-label {
      position: relative;
      z-index: 2;
      font-weight: bold;
      color: #000;
      transition: transform 0.3s ease;
    }

    .visit-btn:hover .visit-label {
      transform: scale(1.1);
    }
    
    .section {
      position: relative;
      padding: 80px 20px;
      text-align: center;
      background-color: #FFDE9E;
      overflow: hidden;
    }

    .container-title {
      font-size:  clamp(1rem, 3vw + 1rem, 6rem);
      color: #ff9000;
      text-shadow: 2px 2px #ffcc00;
      letter-spacing: 4px;
    }

    .chewy-regular {
      font-family: "Chewy", system-ui;
      font-weight: 400;
      font-style: normal;
    }

    .btn-visit {
      padding: 0px;
      width: 110px;
      height: 60px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 50% 60% 50% 70% / 80% 60% 50% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .sunday {
      padding: 0px;
      width: 110px;
      height: 60px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 80% 60% 50% 70% / 80% 80% 50% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .thursday {
      width: 120px;
      height: 60px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 40% 30% 10% 40% / 20% 50% 50% 60%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .wednesday {
      width: 150px;
      height: 60px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 70% 60% 40% 80% / 50% 60% 50% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .saturday {
      width: 130px;
      height: 60px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 80% 40% 60% 70% / 40% 60% 30% 80%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .btn-visit:hover, .sunday:hover, .thursday:hover, .wednesday:hover, .saturday:hover, .time-style:hover {
      transform: scale(1.35) rotate(-3deg);
      color: #000000;
      background-color:#ffde9e;
      border: 3px solid #fff3c0;
      animation: bounce 1s ease-in-out;
    }

    .time-style {
      width: 140px;
      height: 60px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 80% 70% 80% 40% / 70% 80% 80% 80%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1); 
    }

    .hero-mascot{
      position: absolute;
      bottom:  25%;
      left: 5%;
      height: 360px;
      /* animation: float 2s ease-in-out infinite; */
      z-index: 4;
    }

    .mascot-form{
      position: absolute;
      bottom:  10%;
      right: 25%;
      height: 360px;
      animation: float 2s ease-in-out infinite;
      z-index: 4;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
    }

    .form-visitor {
      padding: 0px;
      width:  300px;
      height: 80px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 50% 60% 50% 70% / 80% 60% 50% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .form-plan-visit {
      padding: 0px;
      width:  350px;
      height: 45px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 50% 60% 65% 50% / 60% 65% 80% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .terms-intern {
      padding-top: 20px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 100% 0% 100% 0% / 15% 85% 15% 85%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
      min-height: 320px;
      max-height: 330px;
    }

    .terms-public {
      padding-top: 20px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 0% 100% 0% 100% / 85% 15% 85% 15%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
      min-height: 320px;
      max-height: 330px;
    }

    .dynapuff-regular {
      font-family: "DynaPuff", system-ui;
      font-optical-sizing: auto;
      font-weight: 400;
      font-style: normal;
      font-variation-settings:
      "wdth" 100;
    }

    .wave-title span {
      font-family: "DynaPuff", system-ui;
      font-weight: 400;
      font-style: normal;
      display: inline-block;
      animation: wave 2s infinite ease-in-out;
      font-size: 3rem;
      color: #ff9000;
      text-shadow: 1px 1px #ffcc00;
    }

    .wave-title span:nth-child(odd) {
      animation-delay: 0.3s;
    }

    .wave-title span:nth-child(even) {
      animation-delay: 0.5s;
    }

    @keyframes wave {
      0%, 100% { transform: translateY(0deg) rotate(0deg); }
      50% { transform: translateY(-5px) rotate(-2deg); }
    }

    .card-form-plan-visit {
      padding: 20px;
      width:  100%;
      height: 100%;
      background-color: #fff3c0;
      color: #000;
      border-radius: 40% 20% 60% 50% / 30% 40% 50% 30%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      transition: transform 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

  </style>

  </head>
  <body>
  <!-- Top bar -->
  <div class="custom-navbar sticky-top bg-light">
    <!-- Topbar -->
    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom yellow-bg" style="top: 1rem;">
      <div>
        <a href="/library-public" class="text-decoration-none text-dark d-flex align-items-center">
          {{-- <img src="{{ asset('images/greta-face.png') }}" alt="Logo" style="height: 25px;" class="logo"> --}}
          <span>
            <img src="{{asset('images/logo-school.png')}}" alt="" style="width: 150px;">
          </span> 
        </a>
      </div>

      <div class="d-none d-md-block fw-bold">
        <span class="me-3 text-lg">Great Crystal School・Library</span>
        <a href="#"><i class="bi bi-facebook me-2"></i></a>
        <a href="#"><i class="bi bi-instagram me-2"></i></a>
        <a href="#"><i class="bi bi-youtube"></i></a>
      </div>

      <!-- Hamburger (only visible on mobile) -->
      <button class="d-md-none bg-transparent border-0 fs-4 text-dark" onclick="toggleMobileNav()">
        <i class="bi bi-list"></i>
      </button>
    </div>

    <nav class="d-none d-md-block navbar navbar-expand-md yellow-bg">
      <div class="container">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link visit-btn" href="/library-public"><span class="visit-label">
            Home <i class="bi bi-caret-down-fill"></i>
          </span></a></li>
          <li class="nav-item custom-dropdown">
            <a class="nav-link  visit-btn" href="#" id="visitDropdown" role="button">
              <span class="visit-label">
                Visit <i class="bi bi-caret-down-fill"></i>
              </span>
              </a>
              {{-- <ul class="dropdown-menu custom-dropdown-menu">
                <li><a class="dropdown-item" href="/visit">Plan Your Visit</a></li>
                <li><a class="dropdown-item" href="#">General Admission</a></li>
                <li><a class="dropdown-item" href="#">Hours and Location</a></li>
                <li><a class="dropdown-item" href="#">Birthday Parties</a></li>
                <li><a class="dropdown-item" href="#">Field Trips & Group Visits</a></li>
                <li><a class="dropdown-item" href="#">Facility Rentals</a></li>
                <li><a class="dropdown-item" href="#">Accessibility</a></li>
              </ul> --}}
          </li>
          <li class="nav-item">
            <a class="nav-link visit-btn" href="/explore-library">
              <span class="visit-label">
                Explore <i class="bi bi-caret-down-fill"></i>
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link visit-btn" href="/facility">
              <span class="visit-label">
                Facility <i class="bi bi-caret-down-fill"></i>
              </span>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link visit-btn" href="/others"><span class="visit-label">
            Others <i class="bi bi-caret-down-fill"></i>
          </span></a></li>
        </ul>
      </div>
    </nav>

    <!-- Overlay Menu -->
    <div id="mobileNavOverlay" class="mobile-nav-overlay d-md-none">
      <div class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
        <img src="{{ asset('images/greta-face.png') }}" alt="Logo" style="height: 20px;">
        <button class="btn btn-link text-dark fs-3" onclick="toggleMobileNav()">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
      <div class="nav flex-column px-4 py-3 gap-3">
        <a class="fw-bold nav-link">Visit <i class="bi bi-caret-down-fill"></i></a>
        <a href="/explore-library" class="fw-bold nav-link">Explore <i class="bi bi-caret-down-fill"></i></a>
        <a href="/booking" class="fw-bold nav-link">Booking <i class="bi bi-caret-down-fill"></i></a>
        <a href="/facility" class="fw-bold nav-link">Facility <i class="bi bi-caret-down-fill"></i></a>
      </div>
    </div>
  </div>

  <div class="mobile-nav-backdrop" id="backdrop" onclick="toggleNavbar()"></div>
  <!-- Hero Section -->

  <div class="section" style="background-image: url('images/blob-scene-haikei-3.svg');background-size:cover; 
    background-position: center;">
    <div class="container text-ceneter">
      <h4 class="container-title chewy-regular text-uppercase mb-5">📚 Terms & Conditions Visitation</h4>
    </div>
    <div class="row d-flex justify-content-center text-center align-item-center gap-4">
      <div class="col-12 col-md-5 col-lg-5 terms-intern" data-aos="flip-up" data-aos-duration="600">
        <img src="{{asset('images/greta-face.png')}}" alt="" style="width:50px;height:50px;">
        <h1 class="wave-title fw-bold">
          <span>I</span><span>N</span><span>T</span><span>E</span><span>R</span><span>N</span><span>A</span><span>L</span>
        </h1>
        <div class="text-end" style="padding-right: 30px;">  
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;">Click the <strong class="text-lg">“Booking”</strong> button on the top menu. <span class="text-primary">✔️</span></li>
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;">Fill in the required information in the booking form. <span class="text-primary ">✔️</span></li>
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;">Select the date and time for your visit. <span class="text-primary">✔️</span></li>
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;">Submit the form to confirm your booking. <span class="text-primary">✔️</span></li>
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;">You will receive a confirmation email with visit details. <span class="text-primary">✔️</span></li>
        </div>
      </div>
      <div class="col-12 col-md-5 col-lg-5 terms-public" data-aos="flip-down" data-aos-duration="900">
        <img src="{{asset('images/greta-face.png')}}" alt="" style="width: 50px;height:50px;">
        <h1 class="wave-title fw-bold">
          <span>P</span><span>U</span><span>B</span><span>L</span><span>I</span><span>C</span>
        </h1>
        <div class="text-start" style="padding-left:30px;">
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;"><span class="text-primary">✔️</span> Click the <strong class="text-lg">“Booking”</strong> button on the top menu.</li>
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;"><span class="text-primary">✔️</span> Fill in the required information in the booking form.</li>
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;"><span class="text-primary">✔️</span> Select the date and time for your visit.</li>
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;"><span class="text-primary">✔️</span> Submit the form to confirm your booking.</li>
          <li class="text-decoration-none pb-2 text-lg" style="list-style: none;"><span class="text-primary">✔️</span> Submit the form to confirm your booking.</li>
        </div>
      </div>
    </div>
  </div>

  {{-- JADWAL BUKA PERPUSTAKAAN --}}
  <div class="section" style="background-image: url('images/book-scene-haikei-2.svg');background-size:cover; 
    background-position: center;">
    <div class="container text-center">
      <h1 class="container-title chewy-regular text-uppercase mb-5">
        🗓️ LIBRARY SCHEDULE
      </h1>
    </div>
    <div class="d-flex justify-content-center align-items-center text-center gap-4">
      <img class="hero-mascot d-none d-md-block" src="{{ asset('/images/greta-greti-baca-buku.png')}}" alt="">
      <div class="d-none d-md-block col-md-2">
      </div>
      <div class="col-12 col-md-10">
        <div class="d-flex justify-content-center align-items-center text-center gap-4">
          <div class="row d-grid gap-4">
            <a class="btn-visit" data-aos="flip-up" data-aos-duration="400"><span class="dynapuff-regular text-lg">Day</span></a>
            <a class="btn-visit" data-aos="flip-down" data-aos-duration="600"><span class="dynapuff-regular text-lg">Sunday</span></a>
            <a class="btn-visit" data-aos="flip-up" data-aos-duration="800"><span class="dynapuff-regular text-lg">Monday</span></a>
            <a class="btn-visit" data-aos="flip-down" data-aos-duration="1000"><span class="dynapuff-regular text-lg">Thursday</span></a>
            <a class="btn-visit" data-aos="flip-up" data-aos-duration="1200"><span class="dynapuff-regular text-lg">Wednesday</span></a>
            <a class="btn-visit" data-aos="flip-down" data-aos-duration="1400"><span class="dynapuff-regular text-lg">Tuesday</span></a>
            <a class="btn-visit" data-aos="flip-up" data-aos-duration="1600"><span class="dynapuff-regular text-lg">Friday</span></a>
            <a class="btn-visit" data-aos="flip-down" data-aos-duration="1800"><span class="dynapuff-regular text-lg">Saturday</span></a>
          </div>
          <div class="row d-grid gap-4">
            <a class="time-style" data-aos="flip-down" data-aos-duration="400"><span class="dynapuff-regular text-lg">Time</span></a>
            <a class="time-style" data-aos="flip-up" data-aos-duration="600"><span class="dynapuff-regular text-lg">Close</span></a>
            <a class="time-style" data-aos="flip-down" data-aos-duration="800"><span class="dynapuff-regular text-lg">10:00 - 15:00</span></a>
            <a class="time-style" data-aos="flip-up" data-aos-duration="1000"><span class="dynapuff-regular text-lg">10:00 - 15:00</span></a>
            <a class="time-style" data-aos="flip-down" data-aos-duration="1200"><span class="dynapuff-regular text-lg">09:00 - 15:00</span></a>
            <a class="time-style" data-aos="flip-up" data-aos-duration="1400"><span class="dynapuff-regular text-lg">09:00 - 15:00</span></a>
            <a class="time-style" data-aos="flip-down" data-aos-duration="1600"><span class="dynapuff-regular text-lg">13:00 - 16:00</span></a>
            <a class="time-style" data-aos="flip-up" data-aos-duration="00"><span class="dynapuff-regular text-lg">Close</span></a>
          </div>
        </div>
      </div>
    </div>
  </div>


  {{-- FORM PENGISIAN KUNJUNGAN PERPUSTAKAAN UNTUK SISWA --}}
  @if (session('role') == 'library')
   <div class="section" style="background-image: url('images/book-scene-haikei.svg');background-size:cover; 
      background-position: center;height:100vh">
      <div class="container text-center">
        <h1 class="container-title chewy-regular text-uppercase mb-5">
          📋 Library Guest Filling Form
        </h1>
      </div>
      <div class="d-flex">
        <div class="col-12 col-md-8">
          <div class="row d-flex justify-content-center align-items-center text-center col-12">
            <div class="form-visitor">
              <form action="{{route('visit.student')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="name" class="form-label text-xl">Username</label>
            </div>
          </div>
          <div class="row d-flex justify-content-center align-items-center text-center gap-2">
            <div class="col-6 col-md-4 p-0">
              <input type="text" class="form-control form-visitor w-100 text-center text-lg" id="username" name="username" placeholder="..."  required>
            </div>
            <div class="col-4 col-md-3 p-0">
              <button class="btn btn-sm btn-success form-visitor w-100 text-center text-xl fw-bold" type="submit">Submit</button>
            </div>
            </form> 
          </div>
        </div>
        <div class="d-none d-md-block col-md-4">
        </div>
        <img src="{{asset('/images/greta-care.png')}}" alt="" class="d-none d-md-block mascot-form">
      </div>
    </div>
  @endif

  {{-- PLAN  Visit --}}
  <div class="section" style="background-image: url('images/book-scene-haikei.svg');background-size:cover;height:100vh;">
    <div>
      <h2 class="container-title chewy-regular text-uppercase mb-5">
        Plan Your Visit
        {{-- <span>Pl</span><span>an</span> <span>Yo</span><span>ur</span> <span>Vis</span> <span>it</span> --}}
      </h2>
      <p class="text-xl mb-5">
        Discover the comfort of reading and exploring knowledge in a quiet, inspiring space. <br> Book today, and make your time more meaningful with our curated collection of books!
      </p>
    </div>
    <div class="d-flex justify-content-center align-items-center text-center">
      <div class="col-12 col-md-6 card-form-plan-visit text-center">
        <form action="{{route('action.plan.visit')}}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group grid d-md-flex align-items-center">
            <label for="name" class="form-label text-lg">Name : </label>
            <input type="text" name="name" class="ml-2 form-plan-visit form-control text-center" required>
          </div>
          <div class="form-group grid d-md-flex align-items-center">
            <label for="phone" class="text-lg">Phone : </label>
            <input type="number" name="phone" class="ml-2 form-plan-visit form-control text-center" required>
          </div>
          <div class="form-group grid d-md-flex align-items-center">
            <label for="phone" class="text-lg">Email : </label>
            <input type="mail" name="email" class="ml-2 form-plan-visit form-control text-center" required>
          </div>
          <div class="form-group grid d-md-flex align-items-center">
            <label for="address" class="text-lg">Address : </label>
            <input type="text" name="address" class="ml-2 form-plan-visit form-control text-center" required>
          </div>
          <div class="form-group grid d-md-flex align-items-center">
            <label for="address" class="text-lg">Plan visit : </label>
            <input type="date" name="plan_visit" class="ml-2 form-plan-visit form-control text-center" required>
          </div>
          <button class="btn btn-success form-plan-visit text-center text-lg fw-bold" type="submit">Submit</button>
        </form>
      </div>
      <div class="d-none d-md-block col-md-4">
      </div>
      <img src="{{asset('/images/greta-baca-buku.png')}}" alt="" class="d-none d-md-block mascot-form">
    </div>
  </div>

  {{-- <footer>
      <div class="container text-center py-4 border-top">
          <p>&copy; 2025 Great Crystal School and Course Center. All rights reserved.</p>
      </div>
  </footer> --}}


  <link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <script>
    AOS.init();
    
    function toggleMobileNav() {
      document.getElementById('mobileNavOverlay').classList.toggle('show');
    }

    function logout(){
      Swal.fire({
        title: "Are you sure want to exit?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonColor: "#d33",
        confirmButtonColor: "#00b527",
        confirmButtonText: "Continue",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
              headers: {
                  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                      "content"
                  ),
              },
              accepts: {
                  mycustomtype: "application/x-some-custom-type",
              },
              url: `/logout`,
              type: "GET",
              cache: false,
              // data: {
              //     id: value,
              //     _token: token,
              // },
          })
          .then((res) => {
              if (res.success) {
                  window.location.href = "/explore-library";
              } else {
                  Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "Something went wrong!",
                      footer: '<a href="#">Why do I have this issue?</a>',
                  });
              }
          })
          .catch((err) => {
              Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Something went wrong!",
                  footer: '<a href="#">Why do I have this issue?</a>',
              });
          });
        }
      });
    }
  </script>

  @if (session('success'))
  <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "Silahkan masuk ke perpustakaan",
        timer: 2500,
        showConfirmButton: false
    });
  </script>
  @endif

  @if (session('error'))
  <script>
    Swal.fire({
        icon: 'error',
        title: 'Unregistered Account',
        text: "Please contact the receptionist",
        timer: 4000,
        showConfirmButton: false
    });
  </script>
  @endif
  
  @if (session('plan_visit'))
  <script>
    Swal.fire({
        icon: 'success',
        title: 'Thank You',
        text: "We have received your visit form. We will send you an email for more info",
        timer: 4000,
        showConfirmButton: false
    });
  </script>
  @endif

</body>
</html>
