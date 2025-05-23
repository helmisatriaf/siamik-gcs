<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Great Crystal-Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('/style.css') }}">
  <link rel="stylesheet" href="{{ asset('template') }}/dist/css/adminlte.min.css">

  <style>
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
        </li>
        <li class="nav-item">
          <a class="nav-link visit-btn" href="/explore-library">
            <span class="visit-label">
              Explore <i class="bi bi-caret-down-fill"></i>
            </span>
          </a>
        <li class="nav-item"><a class="nav-link visit-btn" href="/facility"><span class="visit-label">
          Facility <i class="bi bi-caret-down-fill"></i>
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

<!-- Hero Section -->
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-lg border-0 rounded-4 p-4" style="background: #fefefe;">
        <div class="text-center mb-4">
          <h3 class="fw-bold" style="font-family: 'Raleway', sans-serif; color: #2c3e50;">
            📖 How to Borrow a Book
          </h3>
          <p class="text-muted mb-0">It’s easy and quick. Follow these simple steps 👇</p>
        </div>
        <ul class="list-unstyled px-md-4 px-2">
          <li class="mb-3 d-flex align-items-start">
            <span class="mr-3" style="font-size: 1.5rem;">🖱️</span>
            <div>
              <strong>Click</strong> the <code>Booking</code> button on the top menu.
            </div>
          </li>
          <li class="mb-3 d-flex align-items-start">
            <span class="mr-3" style="font-size: 1.5rem;">📅</span>
            <div>
              <strong>Select</strong> your preferred <strong>date and time</strong> to visit.
            </div>
          </li>
          <li class="mb-3 d-flex align-items-start">
            <span class="mr-3" style="font-size: 1.5rem;">📝</span>
            <div>
              <strong>Fill in</strong> your information and hit <strong>submit</strong>.
            </div>
          </li>
          <li class="mb-1 d-flex align-items-start">
            <span class="mr-3" style="font-size: 1.5rem;">📩</span>
            <div>
              <strong>Check</strong> your email for a confirmation message.
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
<script>
  function toggleMobileNav() {
    document.getElementById('mobileNavOverlay').classList.toggle('show');
  }
</script>
</body>
</html>
