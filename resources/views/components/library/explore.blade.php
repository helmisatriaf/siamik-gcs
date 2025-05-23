<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Library ・ Great Crystal School | Explore</title>
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

    .section {
      position: relative;
      text-align: center;
      background-color: #FFDE9E;
      overflow: hidden;
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

    .visit-btn {
      position: relative;
      display: inline-block;
      overflow: hidden;
      border-radius: 4px;
      transition: transform 0.3s ease;
    }

    .visit-label {
      position: relative;
      z-index: 2;
      font-weight: bold;
      color: #000;
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

    /* Optional: text zoom effect on hover */
    .visit-btn:hover .visit-label {
      transform: scale(1.1);
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

    .modal-header.bg-info {
      background: linear-gradient(90deg, #17a2b8, #138496);
    }
    .modal-content {
      border-radius: 1rem;
    }
    .list-group-item {
      border: none;
      padding: 0.75rem 1rem;
      background-color: #f9f9f9;
      margin-bottom: 5px;
      border-radius: 0.5rem;
    }
    .offcanvas {
      width: 250px;
    }

    /* Overlay background */
  .navbar-collapse {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 150px;
    background-color: #fff;
    z-index: 1050;
    padding-top: 60px;
    transition: transform 0.3s ease-in-out;
    transform: translateX(-100%);
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
  }

  .navbar-collapse.show {
    transform: translateX(0);
  }

  .navbar-toggler {
    z-index: 1060;
  }

  /* Optional: dark backdrop */
  .mobile-nav-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 100vw;
    background-color: rgba(0, 0, 0, 0.3);
    z-index: 1040;
    display: none;
  }

  .mobile-nav-backdrop.active {
    display: block;
  }

  .custom-books-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(160px, 1fr));
    gap: ;
  }

  @media (min-width: 768px) {
    .custom-books-grid {
      grid-template-columns: repeat(5, 1fr);
    }
  }

  .content {
    /* background: linear-gradient(145deg, #ffde9e, #ffe8d6); 
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
    padding: 0px;
    border: 3px dashed #ff90b3;  */
  }

  .btn-action {
    padding: 5px;
    background-color: #fff3c0;
    width: 100%;
    color: #000;
    border-radius: 20% 70% 20% 80% / 30% 50% 30% 60%;
    border: 3px solid #ffde9e;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    animation: shakeX 4s infinite ease-in-out;
    font-size: 12px;
  }

  .btn-cancel {
    padding: 0px;
    background-color: #fff3c0;
    width: 20px;
    color: #000;
    border-radius: 60% 70% 60% 80% / 40% 50% 55% 60%;
    border: 3px solid #fff3c0;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    animation: shakeX 4s infinite ease-in-out;
    font-size: 12px;
  }

  .btn-cancel:hover {
    background-color: #ffde9e;
    border: 3px solid #ffde9e;

  }

  .btn-action:hover {
    animation: shakeX 4s infinite ease-in-out;
  }

  .card-book {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
    padding: 0px;
    border: 3px dashed #ffde9e; /* Tambahkan efek lucu */
  }

  .card-book:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  }

  /* Tambahan efek gelembung di sudut */
  .card-book::after {
    content: "";  
    position: absolute;
    top: -20px;
    right: -20px;
    width: 60px;
    height: 60px;
    background: rgba(255, 192, 203, 0.4);
    border-radius: 50%;
    z-index: 0;
  }

  /* Styling teks judul */
  .card-book .card-title {
    font-family: "Chewy", cursive;
    font-size: 1.2rem;
    color: #582f0e;
    text-shadow: 1px 1px #fff;
    letter-spacing: 1px;
  }


  .card-book {
    animation: popIn 0.6s ease forwards;
    opacity: 0;
  }

  @keyframes popIn {
    0% {
      transform: scale(0.95);
      opacity: 0;
    }
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }

  .wave-title span {
    font-family: "Chewy", system-ui;
    font-weight: 400;
    font-style: normal;
    display: inline-block;
    animation: wave 2s infinite ease-in-out;
    font-size:  clamp(1rem, 3vw + 1rem, 6rem);
    color: #ff9000;
    text-shadow: 2px 2px #ffcc00;
    letter-spacing: 4px;
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

  @keyframes shakeX {
    0% { transform: translateX(0) rotate(0deg); }
    25% { transform: translateX(-1px) rotate(-1deg); }
    50% { transform: translateX(1px) rotate(1deg); }
    75% { transform: translateX(-0.5px) rotate(-0.5deg); }
    100% { transform: translateX(0) rotate(0deg); }
  }

  .search-input {
    padding: 10px 45px;
    width: 400px;
    height: 50px;
    background-color: #fff3c0;
    color: #000;
    border-radius: 40% 60% 20% 70% / 40% 60% 50% 70%;
    border: 3px solid #ffde9e;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
  }

  .btn-search {
    /* font-size: 24px;
    padding: 2px;
    background-color: #fff3c0;
    color: #000;
    border-radius: 100% 100% 30% 100% / 90% 100% 90% 90%;
    border: 2px solid #ffde9e;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1); */
    background: transparent;
    border: none;
    font-size: 3rem;
    padding: 0.3rem 0.6rem;
    cursor: pointer;
    text-decoration: none;
    box-shadow: none;
  }

  .search-input:hover {
    transform: scale(1.05) rotate(-3deg);
    color: #000000;
    background-color:#ffde9e;
    border: 3px solid #fff3c0;
    animation: bounce 1s ease-in-out;
  }

  .btn-search:hover {
    transform: scale(1.4) rotate(-3deg);
    /* color: #000000; */
    /* background-color:#ffde9e;
    border: 3px solid #fff3c0; */
    /* animation: bounce 1s ease-in-out; */
  }

  .badge-return {
    padding: 5px;
    width: 100%;
    background-color: #723506;
    color: #fff;
    border-radius: 70% 60% 30% 70% / 60% 80% 60% 90%;
    border: 3px solid #723506;
    display: grid;
    overflow: hidden;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
  }
  
  .badge-loan {
    padding: 5px;
    width: 100%;
    background-color: #A8DADC;
    color: #000;
    border-radius: 70% 60% 30% 70% / 60% 80% 60% 90%;
    border: 3px solid #A8DADC;
    display: grid;
    overflow: hidden;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
  }

  .badge-wait {
    padding: 5px;
    width: 100%;
    background-color: #138496;
    color: #fff;
    border-radius: 70% 60% 30% 70% / 60% 80% 60% 90%;
    border: 3px solid #138496;
    display: grid;
    overflow: hidden;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
  }

  .input-custom {
    padding: 10px 45px;
    width: 100%;
    height: 50px;
    background-color: #fff3c0;
    color: #000;
    border-radius: 40% 60% 50% 70% / 40% 60% 50% 70%;
    border: 3px solid #ffde9e;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
  }

  .custom-modal {
    padding: 50px;
    background-color: #ffcc00;
    color: #000;
    /* border-radius: 70% 80% 60% 80% / 50% 40% 70% 40%; */
    border-radius: 76% 24% 64% 36% / 16% 70% 30% 84% ;
    border: 3px solid #ffcc00;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
  }

  .custom-modal-info {
    padding: 25px;
    background-color: #ffde9e;
    color: #000;
    border-radius: 76% 24% 64% 36% / 16% 70% 30% 84% ;
    border: 3px solid #fff3c0;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
  }
  
  .custom-modal-reserve {
    padding: 20px 10px;
    background-color: #ffde9e;
    color: #000;
    /* border-radius: 40% 10% 10% 40% / 30% 10% 10% 30%; */
    border-radius: 91% 9% 88% 12% / 10% 91% 9% 90% ;
    border: 3px solid #fff3c0;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
  }
  
  .btn-custom-login {
    background-color: #fff3c0;
    color: #000;
    border-radius: 70% 60% 40% 70% / 50% 80% 40% 90%;
    border: 3px solid #ffde9e;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btn-custom-submit {
    background-color: #fff3c0;
    color: #000;
    border-radius: 72% 28% 79% 21% / 42% 65% 35% 58% ;
    border: 3px solid #ffde9e;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .custom-modal-title {
    background-color: #fff3c0;
    color: #000;
    padding: 12px;
    border-radius: 40% 60% 80% 70% / 20% 40% 40% 20%;
    border: 3px solid #ffde9e;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .custom-modal-title-reserve {
    background-color: #ffe8d6;
    color: #000;
    padding: 12px;
    border-radius: 79% 21% 75% 25% / 25% 79% 21% 75%;
    border: 3px solid #ffe8d6;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .quote { 
    font-optical-sizing: auto;
    font-weight: 400;
    font-style: normal;
    position: absolute;
    padding: 15px;
    width: 400px;
    height: 150px;
    background-color: #ffde9e;
    color: #000;
    border-radius: 60% 40% 50% 30% / 40% 50% 30% 40%;
    border: 3px solid #fff3c0;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    top: 10%;
    left: 9%;
    z-index: 1;
  }

  </style>
</head>
<body>

<!-- Mobile Fullscreen Navbar -->
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
      {{-- <a href="#"><i class="bi bi-facebook me-2"></i></a>
      <a href="#"><i class="bi bi-instagram me-2"></i></a>
      <a href="#"><i class="bi bi-youtube"></i></a> --}}
      @if (session('role') !== null)
        <button onclick="logout()" id="log-out" type="button" class="btn-logout btn-danger btn-xs">Exit</button>
      @endif
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
          <a href="/visit" class="nav-link  visit-btn" href="#" id="visitDropdown" role="button">
            <span class="visit-label">
              Visit <i class="bi bi-caret-down-fill"></i>
            </span>
            </a>
            {{-- <ul class="dropdown-menu custom-dropdown-menu">
              <li><a class="dropdown-item" href="#">Plan Your Visit</a></li>
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
        <li class="nav-item"><a class="nav-link visit-btn" href="/facility"><span class="visit-label">
          Facility <i class="bi bi-caret-down-fill"></i>
        </span></a></li>
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
      <a href="/visit" class="fw-bold nav-link">Visit <i class="bi bi-caret-down-fill"></i></a>
      <a href="/explore-library" class="fw-bold nav-link">Explore <i class="bi bi-caret-down-fill"></i></a>
      <a href="/booking" class="fw-bold nav-link">Booking <i class="bi bi-caret-down-fill"></i></a>
      <a href="/facility" class="fw-bold nav-link">Facility <i class="bi bi-caret-down-fill"></i></a>
    </div>
  </div>
</div>

<div class="mobile-nav-backdrop" id="backdrop" onclick="toggleNavbar()"></div>

<!-- Navigation -->
{{-- <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container">
    <button class="navbar-toggler" type="button" onclick="toggleNavbar()">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div>
      <a href="/library-public" class="text-decoration-none text-dark d-flex align-items-center">
        <img src="{{ asset('images/greta-face.png') }}" alt="Logo" style="height: 20px;"> <!-- Ganti dengan logo -->
        <span class="ms-2 fw-bold">Great Crystal School・Library</span>
      </a>
    </div>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav flex-column">
        <li class="nav-item pl-2 border-bottom"><a class="nav-link" href="/visit">Visit</a></li>
        <li class="nav-item pl-2 border-bottom bg-danger"><a class="nav-link" href="/explore-library">Explore</a></li>
        <li class="nav-item pl-2 border-bottom"><a class="nav-link" href="/booking">Booking</a></li>
        <li class="nav-item pl-2 boreder-bottom"><a class="nav-link" href="facility">Facility</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="mobile-nav-backdrop" id="backdrop" onclick="toggleNavbar()"></div> --}}

<!-- Hero Section -->


<div class="section pb-5" style="background-image: url('images/blob-scatter-haikei.svg');background-size:cover; 
  background-position: center;min-height:100vh;">
  <div class="container">
    <h2 class="wave-title">
      <span>🌍</span><span>O</span><span>u</span><span>r</span> 
      <span>C</span><span>o</span><span>l</span><span>l</span><span>e</span><span>c</span><span>t</span><span>i</span><span>o</span><span>n</span>
    </h2>

    @if (count($books) != 0)
    <div class="row custom-books-grid">
        @foreach ($books as $book)
        <div class="content">
          <div>
            <h5 class="fw-bold">
              {{ ucwords(Str::limit($book->title, 22, '...')) }}
            </h5>
          </div> 
            <a href="" data-toggle="modal" data-target="#detailBooked-{{$book->id}}">
              <div class="card-book">
                @if ($book->cover_image == null)
                <img src="{{ asset('images/cover_book.jpg') }}" alt="" class="img-fluid">
                @else
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="" class="img-fluid">
                @endif
              </div>
            </a>

          <div class="info-card" style="padding: 0px;">
            <div class="p-2">
              @php
                $isAdmin = session('role') == 'library' || session('role') == 'superadmin';
                $isUser = session('role') != null && !$isAdmin;
                $isGuest = session('role') == null;

                $isOutOfStock = $book->total == 0;
                $isBooked = count($book->reserve) > 0;
              @endphp

              @if (session('role') != null)
                {{-- Admin/Library Role --}}
                @if ($isAdmin)
                  @if ($isOutOfStock)
                    <span class="btn-action btn btn-sm btn-secondary w-100 disabled">📦 Stock 0</span>
                  @else
                    @if (!$isBooked)
                      <span class="btn-action btn btn-sm btn-info w-100">📚 Stock {{ $book->total }}</span>
                    @else
                      <a class="btn-action btn btn-sm btn-success w-100" data-toggle="modal" data-target="#infoBook-{{ $book->id }}">
                        🔍 View Booked
                      </a>
                    @endif
                  @endif

                {{-- Student/User Role --}}
                @elseif ($isUser)  
                  @if ($isOutOfStock)
                    <span class="btn-action btn btn-sm btn-secondary w-100 disabled">📦 Empty</span>
                  @else
                    @if (!$isBooked)
                      @if ($book->fullBooked() == 1)
                        <a class="btn-action btn btn-sm btn-danger w-100" data-toggle="modal" data-target="#infoBook-{{ $book->id }}">
                          📕 Full Booked
                        </a>
                      @else
                        <a class="btn-action btn btn-sm btn-success w-100" 
                          @if ($isGuest)
                            data-toggle="modal" data-target="#modalLogin"
                          @else
                            onclick="showAlert({{ $book->id }})"
                          @endif>
                          📘 Borrow
                        </a>
                      @endif
                    @else
                      <span class="btn-action btn btn-sm btn-danger w-100 disabled">You Already Booked ✔</span>
                    @endif
                  @endif
                @endif
              @else
                {{-- Guest View --}}
                @if ($isOutOfStock)
                  <a class="btn-action btn btn-sm btn-secondary w-100">
                    📚 Empty
                  </a>
                @else
                  @if ($book->fullBooked() == 1)
                    <a class="btn-action btn btn-sm btn-danger w-100" data-toggle="modal" data-target="#infoBook-{{ $book->id }}">
                      📕 Full Booked
                    </a>
                  @else
                    <a class="btn-action btn btn-sm btn-primary w-100" data-toggle="modal" data-target="#modalLogin">
                      📘 Borrow
                    </a>
                  @endif
                @endif
              @endif

            </div>
          </div>
        </div>
          
        <div class="modal fade" id="infoBook-{{$book->id}}" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content shadow rounded-4">
              <div class="modal-header bg-success text-white rounded-top">
                <h5 class="modal-title">📖 Borrowed Book Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
        
              <div class="modal-body">
                @php
                  $reserves = App\Models\Reserve_book::with(['user'])->where('book_id', $book->id)->get();
                @endphp
        
                @if(count($reserves) > 0)
                  <ul class="list-group list-group-flush">
                    @foreach ($reserves as $index => $reserve)
                      <li class="list-group-item d-flex flex-column">
                        <strong class="text-success">#{{ $index + 1 }} - {{ ucwords($reserve->user->username) }}</strong>
                        <small class="text-muted">📅 Booked: {{ \Carbon\Carbon::parse($reserve->reserve_date)->format('l, d F Y') }}</small>
                        <small class="text-muted">📚 Return: {{ \Carbon\Carbon::parse($reserve->return_date)->format('l, d F Y') }}</small>
                      </li>
                    @endforeach
                  </ul>
                @else
                  <div class="alert alert-warning text-center mb-0">
                    📚 No one has borrowed this book yet.
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="detailBooked-{{$book->id}}" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content custom-modal-info">
              <div class="d-flex gap-4">
                <h5 class="modal-title custom-modal-title-reserve fw-bold">📖 Detail Book</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
        
              <div class="modal-body">
                @php
                  $book = App\Models\Book::where('id', $book->id)->first();
                @endphp

                <p class="text-lg chewy-regular text-uppercase fw-bold">Rack : {{ $book->rack }}{{ $book->rack_location }} </p>
                <p class="text-lg chewy-regular text-uppercase fw-bold">Code : {{ $book->code }} </p>
                
                <ul class="list-group list-group-flush">
                </ul>
              </div>
            </div>
          </div>
        </div>

        @endforeach
        
      {{ $books->links() }}
    </div>
    @else
    <div class="">
      <img src="{{ asset('images/greti-cina.png')}}" class="mascot-not-found">
      <p class="quote text-xl">
        "Oops.. The book you are looking for is not available"
      </p>
    </div>
    @endif
  
    <div class="grid" style="min-height: 50vh;">
      <div class="d-flex justify-content-center align-items-center text-center">
        <form action="/explore-library" id="search-form">
          <label for="search-input" class="wave-title">
            <span>Se</span><span>ar</span><span>ch</span> <span>Bo</span><span>ok</span> 
          </label>
          <div class="d-flex justify-content-center align-items-center text-center gap-4">
            <a href="/explore-library" class="btn-search" title="reset">🔄</a>
            <input id="search-input" type="text" class="search-input text-xl text-center" placeholder="..." name="search" autocomplete="off">
            <button class="btn-search" type="submit">🔍</button>
          </div>
        </form>
      </div>
    </div>

    @if (session('role') == 'student')
      @php
        $reservedBooks = \App\Models\Reserve_book::with(['book'])->where('user_id', session('id_user'))->get();
        @endphp
      @if (count($reservedBooks) != 0)
        <div class="mt-4">
          <h2 class="wave-title">
            <span>His</span><span>to</span><span>ry</span> <span>Bor</span><span>row</span> <span>Bo</span><span>ok</span>
          </h2>
          <div class="row custom-books-grid">
            @foreach ($reservedBooks as $reservedBook)
            @php
              $countDay = \Carbon\Carbon::parse($reservedBook->return_date)->diffInDays(\Carbon\Carbon::now());
              $countDay = $reservedBook->status == 1 ? $countDay : 0;
              $tooltips = "$countDay days overdue";
            @endphp
              <div class="content" tabindex="0" data-bs-toggle="tooltip" title="{{ $tooltips }}">
                <div>
                  <h5 class="fw-bold">
                    {{ ucwords(Str::limit($reservedBook->book->title, 22, '...')) }}
                  </h5>
                </div> 
                <a href="" data-toggle="modal" data-target="#infoBook">
                  <div class="card-book">
                    <img src="{{asset('images/cover_book.jpg')}}" alt="" class="img-fluid">
                  </div>
                </a>
                <p class="card-text text-sm fw-bold">
                  @if ($reservedBook->status == 0)
                  <div class="badge-wait d-flex gap-3 fw-bold">
                    Waiting for pickup
                    <button type="button" class="btn btn-sm btn-cancel" data-toggle="modal" title="Cancel loan"
                        id="cancel"
                        data-id="{{ $reservedBook->id }}"
                        >
                        ❌
                    </button>
                  </div>
                  @elseif ($reservedBook->status == 1)
                  <span class="badge-loan">
                    Current loan period
                      Deadline {{ \Carbon\Carbon::parse($reservedBook->return_date)->format('l, d F Y') }} <br>
                    </span>
                  @elseif ($reservedBook->status == 2)
                  <span class="badge-return">
                    Returned ✔ <br>
                    {{ \Carbon\Carbon::parse($reservedBook->return_receive)->format('l, d F Y')}}
                    </span>
                  @endif
                </p>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    @endif
  </div>
</div>

<div class="modal fade" id="modalReserve" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content custom-modal-reserve">
      <div class="d-flex gap-4">
        <h5 class="modal-title custom-modal-title-reserve fw-bold">📖 Borrow Book</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-4">
        @php
          $username = ucwords(strtolower(session('name_user')));
        @endphp

        <form method="POST" action="{{ route('reserve.book') }}" enctype="multipart/form-data">
          @csrf
          
          {{-- Hidden Inputs --}}
          <input type="hidden" name="book_id" id="id-update">
          <input type="hidden" name="user_id" value="{{ session('id_user') }}">

          {{-- User Name --}}
          <div class="form-group mb-3">
            <label for="name" class="fw-bold">👤 Your Name</label>
            <input name="name" type="text" class="form-control input-custom" value="{{ $username }}" readonly>
          </div>

          {{-- Pick-up Date --}}
          <div class="form-group mb-3">
            <label for="date" class="fw-bold">📅 Pick-up Date</label>
            <input 
              name="pick" 
              type="date" 
              class="form-control input-custom" 
              id="pick-date" 
              oninput="setReturnDate(this.value)" 
              required
            >
            <small class="form-text fw-bold text-dark">
              * Maksimum loan 2 Week
            </small>
          </div>

          {{-- Return Date --}}
          <div class="form-group mb-4">
            <label for="date" class="fw-bold">🗓️ Return Date</label>
            <input 
              name="return" 
              type="date" 
              class="form-control bg-light input-custom" 
              id="return-date" 
              readonly
            >
            <small class="form-text fw-bold text-dark">
              * Book pick-up can be done directly to library GCS floor 3
            </small>
          </div>

          {{-- Submit --}}
          <div class="d-grid">
            <button type="submit" class="btn btn-success btn-custom-submit fw-bold">
              ✅ Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content custom-modal">
      <div class="d-flex gap-4">
        <h5 class="modal-title custom-modal-title fw-bold">Login First</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-danger text-center fw-bold">Login with your student account. <br> If you're external people you can read the book in place</p>
        <form method="POST" action="{{ route('login.library') }}">
          @csrf
          <div class="form-group">
            <label for="name">Username</label>
            <input name="username" type="text" class="form-control input-custom">
          </div>

          <div class="form-group">
            <label for="name">Password</label>
            <input name="password" type="text" class="form-control input-custom">
          </div>

          <div class="form-group d-flex justify-content-center align-item-center text-center">
            <input role="button" type="submit" class="btn-custom-login btn btn-success w-fit fw-bold">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


@if (session('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Successfully Borrow Book',
      timer: 2000, // Swal akan hilang dalam 2000ms (2 detik)
      showConfirmButton: false // Sembunyikan tombol "OK",
    });
  </script>
@endif

@if (session('invalid'))
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Oops only student can login on this website',
      timer: 2000, // Swal akan hilang dalam 2000ms (2 detik)
      showConfirmButton: false // Sembunyikan tombol "OK",
    });
  </script>
@endif

<script>
  function showAlert(id) {
    fetch(`/data/reserve/get/${id}`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        Swal.fire("Gagal", data.error, "error");
        console.error("Detail error:", data.details);
      } else {
        // Masukkan data ke form modal
        document.getElementById("id-update").value = data.data.id;

        // Tampilkan modal Bootstrap
        $("#modalReserve").modal("show");
      }
    })
    .catch(error => {
      Swal.fire("Error", "Terjadi kesalahan dalam pengambilan data", "error");
      console.error("Fetch error:", error);
    });
  }

  function setReturnDate(pickupDate) {
    const pickup = new Date(pickupDate);
    const returnDate = new Date(pickup);
    returnDate.setDate(returnDate.getDate() + 14); // Set 2 weeks later
    document.getElementById("return-date").value = returnDate.toISOString().split('T')[0];
  }

  function logout(){
    Swal.fire({
    title: "🚪 Log Out?",
    text: "Are you sure you want to leave the library zone?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, Exit!",
    cancelButtonText: "Cancel",
    background: "#fefae0", // light background
    color: "#333",
    confirmButtonColor: "#43aa8b",
    cancelButtonColor: "#f94144",
    customClass: {
      popup: 'rounded-4 shadow-lg px-4 py-3',
      title: 'fw-bold text-dark',
      confirmButton: 'btn btn-success px-4',
      cancelButton: 'btn btn-danger px-4',
    },
    showClass: {
      popup: 'animate__animated animate__fadeInDown'
    },
    hideClass: {
      popup: 'animate__animated animate__fadeOutUp'
    }
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

  document.addEventListener("DOMContentLoaded", function() {
    const dateInput = document.getElementById("pick-date");
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    dateInput.min = today;
  });

  
  // AOS.init();

  function toggleMobileNav() {
    document.getElementById('mobileNavOverlay').classList.toggle('show');
  }

  $(document).on('click', '#cancel', function() {
      var id = $(this).data('id');
      Swal.fire({
          title: "Cancel Loan ?",
          text: "Clik yes for next",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Iya"
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: "{{ route('cancel.book', ':id') }}".replace(':id', id),
                  type: 'POST',
                  data: {
                      exam_id: id,
                      _token: '{{ csrf_token() }}'
                  },
                  success: function(response) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Cancel loan has done',
                          timer: 1000, // Swal akan hilang dalam 2000ms (2 detik)
                          showConfirmButton: false // Sembunyikan tombol "OK",
                      }).then(function() {
                          location.reload();
                      });
                  },
                  error: function(xhr) {
                      console.log(xhr.responseText);
                      alert("Error occurred!");
                  }
              });
          }
      });
  });
</script>



</body>
</html>
