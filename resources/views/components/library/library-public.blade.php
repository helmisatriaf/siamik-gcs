<!DOCTYPE html>
<html lang="ja">
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
    html,body {
      /* font-family: 'Noto Sans JP', sans-serif; */
      font-family: 'Comic Sans MS', cursive, sans-serif;
      /* background-image: url('{{ asset('images/school.webp') }}');
      background-repeat: repeat;
      background-size: auto; */
      font-family: "Chewy", system-ui;
      font-weight: 400;
      font-style: normal;
    }

    /* HEROOOOOO */
    .hero {
      /* background-image: url('{{ asset("images/building.png") }}');  */
      background-size: cover; 
      background-position: center;
      height: 100vh;
      position: relative;
    }

    .hero-text {
      position: absolute;
      top: 40%;
      left: 50%;
      transform: translate(-50%, -50%);
      /* color: #81ff2d; */
      text-align: center;
    }

    @import url('https://fonts.googleapis.com/css2?family=Chewy&display=swap');

    .chewy-font {
      font-family: 'Chewy', cursive;
    }

    .dynapuff-regular {
      font-family: "DynaPuff", system-ui;
      font-optical-sizing: auto;
      font-weight: 400;
      font-style: normal;
      font-variation-settings:
      "wdth" 100;
    }

    .hero-section {
      background: linear-gradient(145deg, #fffceb, #e8f7ff);
      background-image: url('{{ asset('images/school.webp') }}');
      position: relative;
      min-height: 70vh;

      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .hero-title {
      font-size:  clamp(2rem, 5vw + 1rem, 12rem);
      color: #ff9000;
      text-shadow: 2px 2px #ffcc00;
    }

    .title-fun-fact {
      font-size: clamp(1rem, 1vw + 1rem, 3rem);
    }

    .description-fun-fact {
      font-size: clamp(2rem, 2vw + 2rem, 4rem);
    }

    .container-title {
      font-size:  clamp(1rem, 3vw + 1rem, 6rem);
      color: #ff9000;
      text-shadow: 2px 2px #ffcc00;
      letter-spacing: 4px;
    }

    .hero-mascot {
      position: absolute;
      bottom: 1%;
      right: 10%;
      height: 250px;
      max-width: 100%;
      z-index: 3;
      /* animation: float 4s ease-in-out infinite; */
    }
    .hero-mascot-top-left {
      position: absolute;
      top:  clamp(2%, 5vw + 1%, 4%);
      height: clamp(30px, 5vw + 50px, 250px);
      max-width: 100%;
      animation: float 4s ease-in-out infinite;
    }
    
    .btn-explore {
      font-size: 1.1rem;
      background-color: #ff9000;
      color: white;
      padding: 8px 20px;
      text-decoration: none;
      border-radius: 70% 50% 60% 30% / 80% 60% 60% 35%;
      /* border-radius: 5px; */
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-radius 0.15s ease-in-out;
      display: inline-block;
    }

    .btn-explore:hover {
      transform: scale(1.2) rotate(-3deg);
      color: #ffffff;
      background-color: #e47600;
      border-radius: 70% 50% 60% 30% / 80% 60% 60% 35%;
      animation: bounce 0.3s ease-in-out;
    }

    .btn-fun-fact {
      font-size: 1.1rem;
      background-color: #1D3557;
      color: white;
      padding: 8px 20px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-radius 0.15s ease-in-out;
      display: inline-block;
    }

    .btn-fun-fact:hover {
      transform: scale(1.2) rotate(-3deg);
      color: #1D3557;
      background-color: #A8DADC;
      border-radius: 20px;
      animation: bounce 0.3s ease-in-out;
    }
    
    .btn-search-book {
      font-size: 1.1rem;
      background-color: #5accf8;
      color: white;
      padding: 12px 20px;
      text-decoration: none;
      border-radius: 40% 60% 60% 70% / 60% 80% 50% 70%;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-radius 0.15s ease-in-out;
      display: inline-block;
    }

    .btn-search-book:hover {
      transform: scale(1.2) rotate(-3deg);
      color: #4b5cb7;
      background-color: #A8DADC;
      border-radius: 40% 60% 60% 70% / 60% 60% 50% 70%;
      animation: bounce 0.3s ease-in-out;
    }
    
    .btn-visit {
      margin-top: 5vh;
      padding: 0px;
      width: 140px;
      height: 140px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 50% 60% 20% 70% / 20% 60% 50% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-visit:hover {
      transform: scale(1.35) rotate(-3deg);
      color: #000000;
      background-color:#ffde9e;
      border: 3px solid #fff3c0;
      animation: bounce 1s ease-in-out;
    }
    
    .card-step-1 {
      padding: 15px;
      width: 400px;
      height: 150px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 50% 60% 20% 70% / 20% 60% 50% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .card-step-2 {
      padding: 15px;
      width: 400px;
      height: 150px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 20% 35% 50% 70% / 20% 60% 50% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .card-step-3 {
      padding: 15px;
      width: 400px;
      height: 150px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 50% 35% 50% 30% / 50% 60% 50% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .card-step-4 {
      padding: 15px;
      width: 400px;
      height: 150px;
      background-color: #fff3c0;
      color: #000;
      border-radius: 20% 35% 50% 70% / 20% 60% 50% 70%;
      border: 3px solid #ffde9e;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
    }

    .card-step-1:hover, .card-step-2:hover, .card-step-3:hover, .card-step-4:hover {
      transform: scale(1.35) rotate(-3deg);
      color: #000000;
      background-color:#ffde9e;
      border: 3px solid #fff3c0;
      animation: bouncebig 1s ease-in-out;
      cursor: pointer;
    }

    /* Bounce keyframe */
    @keyframes bounce {
      0%   { transform: scale(1) rotate(0deg); }
      30%  { transform: scale(1.25) rotate(-3deg); }
      60%  { transform: scale(1.15) rotate(-2deg); }
      100% { transform: scale(1.2) rotate(-3deg); }
    }

    @keyframes bouncebig {
      0%   { transform: scale(1.3) rotate(0deg); }
      30%  { transform: scale(1.25) rotate(-3deg); }
      60%  { transform: scale(1.15) rotate(-2deg); }
      100% { transform: scale(1.25) rotate(-3deg); }
    }

    .hero-mascot-bottom-left {
      position: absolute;
      bottom:  33%;
      left: 5%;
      height: 250px;
      max-width: 100%;
      animation: float 2s ease-in-out infinite;
      z-index: 4;
    }

    .fun-fact-card {
      animation: funfactcard 3.5s ease-in-out infinite;
    }

    @keyframes funfactcard {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-4px); }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
    }

    /* END HEROOOOOOO */
    .highlight {
      background: #fff;
      border-radius: 20px;
      padding: 10px 20px;
      box-shadow: 0 5px 10px rgba(0,0,0,0.1);
      display: inline-block;
      font-size: 0.9rem;
    }
    .mascot {
      position: absolute;
      bottom: 20px;
      right: 20px;
      width: 190px;
    }

    .nav-link {
      color: #4a2e1f !important;
    }

    .card-body ul li {
      font-size: 1.05rem;
    }
    
    .chewy-regular {
      font-family: "Chewy", system-ui;
      font-weight: 400;
      font-style: normal;
    }

    .baloo {
      font-family: 'Baloo 2', cursive;
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


    /* Background */
    .section {
      position: relative;
      padding: 80px 20px;
      text-align: center;
      background-color: #FFDE9E;
      overflow: hidden;
    }

    .content-welcome {
      position: relative;
      z-index: 2; /* Pastikan lebih tinggi dari shape */
    }

    .borrow-content {
      position: relative;
      z-index: 2; /* Pastikan lebih tinggi dari shape */
    }

    .custom-shape-divider-bottom-1744616671 {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
        transform: rotate(180deg);
    }

    .custom-shape-divider-bottom-1744616671 svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 85px;
    }

    .custom-shape-divider-bottom-1744616671 .shape-fill {
        fill: #FFDE9E;
    }

    .wave svg {
      position: relative;
      display: block;
      width: calc(150% + 1.3px);
      height: 80px;
    }

    .wave.wave-top svg {
      transform: rotate(180deg);
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

    .brain-growth {
      background-color: #f5f5ff;
      background-image: url('school.webp');
      background-size: 300px;
      background-repeat: repeat;
      padding: 60px 20px;
    }

    .row-benefits {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
    }

    .benefit-card {
      background-color: #fff3c0;
      border: 2px dashed #aaa;
      border-radius: 12px;
      padding: 20px;
      width: 280px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      transition: transform 0.3s;
      text-align: center;
    }

    .benefit-card:hover {
      transform: scale(1.05);
      border-color: #aaa;
    }

    .benefit-icon {
      width: 100%;
      margin-bottom: 15px;
    }

    .custom-shape-divider-bottom-1744617338 {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      overflow: hidden;
      line-height: 0;
      z-index: 1; /* Lebih rendah agar di belakang content */
    }

    .custom-shape-divider-bottom-1744617338 svg {
      position: relative;
      display: block;
      width: calc(100% + 1.3px);
      height: 350px;
    }

    .custom-shape-divider-bottom-1744617338 .shape-fill {
      fill: #FFDE9E;
    }

    .custom-shape-divider-bottom-1744618158 {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
        transform: rotate(180deg);
        z-index: 1;
    }

    .custom-shape-divider-bottom-1744618158 svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 300px;
    }

    .custom-shape-divider-bottom-1744618158 .shape-fill {
        fill: #FFDE9E;
    }

    .custom-shape-divider-bottom-1744618671 {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
        transform: rotate(180deg);
    }

    .custom-shape-divider-bottom-1744618671 svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 87px;
    }

    .custom-shape-divider-bottom-1744618671 .shape-fill {
        fill: #fff3c0;
    }

    .custom-shape-divider-top-1744620027 {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      overflow: hidden;
      line-height: 0;
    }

    .custom-shape-divider-top-1744620027 svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 87px;
    }

    .custom-shape-divider-top-1744620027 .shape-fill {
        fill: #FFDE9E;
    }

    .custom-shape-divider-bottom-1744696324 {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
        transform: rotate(180deg);
        z-index: 1;
    }

    .custom-shape-divider-bottom-1744696324 svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 86px;
    }

    .custom-shape-divider-bottom-1744696324 .shape-fill {
        fill: #FFDE9E;
    }

    /** For tablet devices **/
    @media (min-width: 768px) and (max-width: 1023px) {
        .custom-shape-divider-top-1744620027 svg {
            width: calc(100% + 1.3px);
            height: 166px;
        }
    }

      .fun-card {
        background: #fff9e6;
        transition: transform 2s ease, box-shadow 2s ease;
        border: 2px dashed #ffc107;
      }

      .fun-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
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

      .card-wave-title span {
        font-family: "Chewy", system-ui;
        font-weight: 400;
        font-style: normal;
        display: inline-block;
        animation: shakeX 4s infinite ease-in-out;
        font-size:  3rem;
        color: #ff9000;
        text-shadow: 2px 2px #ffcc00;
        letter-spacing: 4px;
      }

      .card-wave-title span:nth-child(odd) {
        animation-delay: 1s;
      }
      .card-wave-title span:nth-child(even) {
        animation-delay: 2s;
      }

      @keyframes shakeX {
        0% { transform: translateX(0) rotate(0deg); }
        25% { transform: translateX(-2px) rotate(-2deg); }
        50% { transform: translateX(2px) rotate(2deg); }
        75% { transform: translateX(-1px) rotate(-1deg); }
        100% { transform: translateX(0) rotate(0deg); }
      }

      @keyframes wave {
        0%, 100% { transform: translateY(0deg) rotate(0deg); }
        50% { transform: translateY(-5px) rotate(-2deg); }
      }

      .fun-card-custom {
        background-color: #A8DADC; /* Bisa ganti dengan warna random */
        border-radius: 20px;
        padding: 2rem 1rem;
        height: 100%;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        z-index: 1;
        position: relative;
      }

      .fun-card-custom:hover {
        transform: translateY(-30px); /* Naik 10px */
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2); /* Tambahan bayangan untuk efek pop */
        z-index: 2;
        cursor: pointer;
      }


      .flower-shape {
        background-color: #1D3557;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 50%;
        clip-path: polygon(
          50% 0%, 65% 10%, 85% 15%, 100% 30%, 
          100% 50%, 85% 70%, 65% 85%, 50% 100%, 
          35% 85%, 15% 70%, 0% 50%, 0% 30%, 
          15% 15%, 35% 10%
        );
        margin-bottom: 1rem;
      }

      /* Rotasi acak seperti contoh */
      .rotate-0 {
        transform: rotate(-10deg);
      }
      .rotate-1 {
        transform: rotate(0deg);
      }
      .rotate-2 {
        transform: rotate(10deg);
      }

      .card-facility {
        padding: 0px;
        width: 260px;
        height: 260px;
        background-color: #fff3c0;
        border-radius: 30% 40% 30% 40% / 40% 30% 40% 30%;
        border: 3px solid #ffb703;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
      }

      .img-inside-polygon {
        width: 100%;
        height: 100%;
      }

      .card-intro {
        padding: 0px;
        width: 380px;
        height: 380px;
        background-color: #fff3c0;
        border-radius: 30% 40% 30% 40% / 40% 30% 40% 30%;
        border: 3px solid #ffcc00;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
      }
      
      .img-inside-intro {
        width: 100%;
        height: 100%;
      }
      
      .card-content {
        width: 100%;
        height: 180px;
        background-color: #fff3c0;
        border-radius: 80% 60% 50% 60% / 60% 50% 60% 80%;
        border: 3px solid #ffcc00;
        overflow: hidden;
        display: grid;
        align-items: center;
        justify-content: center;
        text-align: center;
        transition: transform 0.3s;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
      }

      .card-content:hover {
        cursor: pointer;
        background-color: #ffde9e;
        border-radius: 80% 60% 50% 60% / 60% 50% 60% 80%;
        border: 3px solid #fff3c0;
        transition: transform 0.3s;
        transform: translateX(10px);
      } 

      .quote { 
        font-family: "Caveat", cursive;
        font-optical-sizing: auto;
        font-weight: 400;
        font-style: normal;
        position: absolute;
        padding: 25px;
        width: 400px;
        height: 150px;
        background-color: #ff9000;
        color: #fff;
        border-radius: 70% 50% 20% 80% / 40% 60% 60% 40%;
        border: 3px solid #fff3c0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
        bottom: 26%;
        right: 16%;
        z-index: 1;
      }
  </style>
</head>

<body>

<!-- Mobile Fullscreen Navbar -->
<div class="custom-navbar sticky-top bg-light">
  <!-- Topbar -->
  <div class="d-flex justify-content-between align-items-center px-3 py-2 yellow-bg" style="top: 1rem;">
    <div>
      <a href="#" class="text-decoration-none text-dark d-flex align-items-center">
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
          <a class="nav-link  visit-btn" href="/visit" id="visitDropdown" role="button">
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

<div class="section overflow-hidden" style="background-image: url('images/symbol-scatter-haikei-5.svg');background-size: ; 
      background-position: ;height:100vh">
  <div class="container text-center" style="position: relative;z-index: 2;">
    <h1 class="hero-title chewy-regular text-uppercase">Welcome to great crystal library</h1>
    <p class="hero-subtitle fw-bold py-4 text-lg" style="letter-spacing: 1px;">GREAT CRYSTAL SCHOOL & COURSE CENTER<br><span class="text-lg">SURABAYA, EAST JAVA</span></p>
    <a href="/explore-library" class="btn-explore"><span class="text-lg">EXPLORE</span></a>
  </div>
  <img src="{{ asset('images/greta-baca-buku.png') }}" alt="Mascot" class="hero-mascot">
  <img src="{{ asset('images/greta-greti-baju-olga.png') }}" class="hero-mascot-bottom-left d-none d-md-block" alt="">
  {{-- <img src="{{ asset('images/greti-baca-buku.png') }}" class="hero-mascot-bottom-left" alt=""> --}}
  <p class="quote text-xl">
    "The more that you read, the more things you will know. The more that you learn, the more places you’ll go." - Dr. Seuss
  </p>
</div>

{{-- WELCOME TO LIBRARY --}}
<div class="section yellow-bg" style="height:100vh;">
  <div class="content-welcome">
    <h1 class="container-title chewy-regular text-uppercase" data-aos="fade-down" data-aos-easing="ease-out-cubic" data-aos-duration="1000">Let's Go to the Library!</h1>
    <div class="container d-flex align-items-center justify-content-center">
      <div class="row d-flex text-center">
        <div class="card-intro" data-aos="fade-right" data-aos-easing="ease-out-cubic" data-aos-duration="1000">
          <img src="{{ asset('images/library.jpg')}}" class="img-fluid img-inside-intro" alt="bglibrary">
        </div>
        <div class="col-12 col-md-7">
          <div class="row d-flex">
            <div class="col-md-6 mb-4" data-aos="flip-up" data-aos-easing="ease-out-cubic" data-aos-duration="1000">
              <a class="card-content" href="/explore-library">
                <h4 class="fw-bold card-wave-title">
                <span>📚</span> <span>C</span><span>o</span><span>l</span><span>l</span><span>e</span><span>c</span><span>t</span><span>i</span><span>o</span><span>n</span><span>s</span></h4>
              </a>
            </div>
        
            <div class="col-md-6 mb-4" data-aos="flip-down" data-aos-easing="ease-out-cubic" data-aos-duration="900">
              <a class="card-content" href="/facility">
                <h4 class="fw-bold card-wave-title">
                  <span>🏫</span> <span>F</span><span>a</span><span>c</span><span>i</span><span>l</span><span>i</span><span>t</span><span>i</span><span>e</span><span>s</span></h4>
                </a>
            </div>
        
            <div class="col-md-6 mb-4" data-aos="flip-up" data-aos-easing="ease-out-cubic" data-aos-duration="700">
              <a class="card-content" href="/others">
                <h4 class="fw-bold card-wave-title"><span>📎</span> <span>O</span><span>t</span><span>h</span><span>e</span><span>r</span><span>s</span></h4>
              </a>
            </div>
  
            <div class="col-md-6 mb-4" data-aos="flip-down" data-aos-easing="ease-out-cubic" data-aos-duration="800">
              <a class="card-content" href="/visit">
                <h4 class="fw-bold card-wave-title"><span>🏫</span> <span>V</span><span>i</span><span>s</span><span>i</span><span>t</span><span>s</span></h4>
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div class="custom-shape-divider-bottom-1744617338">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M741,116.23C291,117.43,0,27.57,0,6V120H1200V6C1200,27.93,1186.4,119.83,741,116.23Z" class="shape-fill"></path>
    </svg>
  </div>
</div>
{{-- END WELCOME TO LIBRARY --}}

{{-- GROW YOUR BRAIN --}}
<div class="section orange-bg">
  <div class="content-grow">
    <h2 class="container-title chewy-regular text-uppercase mb-3" data-aos="zoom-in-down">🧠 Grow Your Brain at the Library</h2>
    <p class="text-xl" data-aos="zoom-out">The library is not just a place to borrow books—here you can grow into a smarter, more creative, and independent person!</p>
  
    <div class="row-benefits">
      <div class="benefit-card" data-aos="zoom-out-left" data-aos-easing="ease-out-cubic" data-aos-duration="800">
        <img src="{{ asset('images/Learning-cuate.svg') }}" alt="Reading Icon" class="benefit-icon">
        <h4 class="fw-bold">📖 Increase Knowledge</h4>
        <p>Every book is a window to the world. Discover new information and hone your curiosity.</p>
      </div>
      <div class="benefit-card" data-aos="zoom-in-up" data-aos-easing="ease-out-cubic" data-aos-duration="600">
        <img src="{{ asset('images/college project-cuate.svg') }}" alt="Creativity Icon" class="benefit-icon">
        <h4 class="fw-bold">🎨 Increase Creativity</h4>
        <p>With storybooks, poems, and comics—your imagination can flourish without limits!</p>
      </div>
      <div class="benefit-card" data-aos="zoom-out-right" data-aos-easing="ease-out-cubic" data-aos-duration="800">
        <img src="{{ asset('images/Teaching-cuate.svg') }}" alt="Focus Icon" class="benefit-icon">
        <h4 class="fw-bold">🧘 Learn Focus & Concentration</h4>
        <p>A quiet reading room helps you learn to be more concentrated and disciplined.</p>
      </div>
    </div>
  </div>
</div>
{{-- END GROW YOUR BRAIN --}}

{{-- FAKTA MENARIK --}}
<div class="section yellow-bg py-5" style="min-height: 100vh;">
  <div class="container" id="fun-facts" style="position: relative;z-index: 2;">
    <h2 class="container-title wave-title mb-5" data-aos="zoom-out-down">
      <span>🌍</span><span>F</span><span>U</span><span>N</span> 
      <span>F</span><span>A</span><span>C</span><span>T</span><span>S</span>
    </h2>

    <div class="row justify-content-center" style="z-index: 999;" data-aos="zoom-in" data-aos-easing="ease-out-cubic" data-aos-duration="900">
      @foreach($randoms as $index => $item)
      <div class="col-12 col-md-4 mb-4 fun-fact-card">
          <div class="fun-card-custom position-relative d-flex flex-column justify-content-center align-items-center text-center rotate-{{ $index }}">
              <div class="flower-shape d-flex justify-content-center align-items-center">
                  <h5 class="text-white fw-bold dynapuff-regular mb-0 px-3 text-xl">{{ $item['title'] }}</h5>
              </div>
              <img src="{{ asset('images/greta-face.png') }}" alt="Fact Icon" class="img-fluid my-3" style="width: 50%;">
              <div class="dynapuff-regular px-3 text-dark text-lg">
                  {{ $item['description'] }}
              </div>
          </div>
      </div>
      @endforeach
    </div>  
    <div>
      <a href="/others" class="btn-fun-fact"><span class="dynapuff-regular" >More Fact 👀</span></a>
    </div>
  </div>
  <div class="custom-shape-divider-top-1744620027" style="z-index: 1;">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
    </svg>
  </div>  
  <div class="custom-shape-divider-bottom-1744696324" style="z-index: 1;">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V6c0,21.6,291,111.46,741,110.26,445.39,3.6,459-88.3,459-110.26V0Z" class="shape-fill"></path>
    </svg>
  </div>
</div>
{{-- END FAKTA MENARIK --}}

{{-- KUNJUNGAN --}}
<div class="section py-5 d-flex align-items-center justify-content-center text-center" style="background-image: url('images/circle-scatter-haikei.svg');background-size: cover; 
  background-position: center;">
  <div class="container borrow-content text-center" id="how-to-borrow">
    <div data-aos="fade-right">
      <h2 class="container-title chewy-regular text-uppercase mb-5">
        🏫 Visit to Great Crystal Library !
      </h2>
      <h5 class="text-xl mb-5">Step into a world of imagination, knowledge, and discovery — all in one place!</h5>
    </div>
    <div class="row g-4">
      <!-- Maskot -->
      <div class="card-facility" data-aos="zoom-out-right">
        <img src="{{ asset('images/IMG_1484.jpg') }}" alt="Library" class="img-inside-polygon" />
      </div>      
      <div class="col-12 col-md-6 d-flex justify-content-center align-items-center text-center" data-aos="zoom-out-down" 
        background-position: center;">
        <img src="{{ asset('images/greta-greti-baju-olga.png') }}" class="img-fluid" alt="Mascot">
      </div>
      <div class="card-facility" data-aos="zoom-out-left">
        <img src="{{ asset('images/welcome-library.jpg') }}" alt="Library" class="img-inside-polygon" />
      </div>      
    </div>
    <div class="d-flex justify-content-center align-items-center text-center">
      <a href="/visit" class="btn-visit"><span class="dynapuff-regular text-xl">Visit 👀</span></a>
    </div>
  </div>
</div>

{{-- PEMINJAMAN BUKU --}}
<!-- Wrapper Section -->
<div class="section orange-bg" style="background-image: url('images/blob-haikei-mid.svg'); background-size: cover; background-position: center;">
  <div class="container borrow-content position-relative" id="how-to-borrow">

    <!-- Title -->
    <div class="text-center mb-5">
      <h4 class="container-title wave-title">
        <span>📖 </span><span>H</span><span>ow</span> <span>to</span> <span>Bor</span><span>row</span> 
        <span>a</span> <span>Bo</span><span>ok</span> <span>?</span><span>?</span><span>?</span></h4>
    </div>

    <!-- Row 1: Step 1 & Step 2 -->
    <div class="row mb-4">
      <div class="col-md-6 d-flex justify-content-start align-items-center text-center" data-aos="fade-right">
        <div class="card-step-1">
          <p class="dynapuff-regular px-3 text-dark text-xl">
            <strong class="text-xl">Click</strong> the <span class="tex-dark"><a href="/explore-library" class="text-xl text-danger font-underlined">📔 reserve</a></span><br>
            button on card book in menu explore.
          </p>
        </div>
      </div>
      <div class="col-md-6 d-flex justify-content-end align-items-end text-center" data-aos="fade-left">
        <div class="card-step-2">
          <p class="dynapuff-regular px-3 text-dark text-xl">
            <strong class="text-xl">Select</strong> the <span class="text-success text-xl">🗓️ date and time</span> you want to book.
          </p>
        </div>
      </div>
    </div>

    <!-- Row 2: Mascot Image Centered, Positioned Absolutely -->
    <div class="position-relative" style="height: 40px;">
      <img src="{{ asset('images/greti-baca-buku.png') }}" 
      class="img-fluid d-none d-md-block"
      alt="greti"
      style="
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-height: 350px;
        width: auto;
        z-index: 1;
        pointer-events: none;
      ">
    </div>

    <!-- Row 3 & 4: Step 3 & Step 4 -->
    <div class="row mt-4">
      <div class="col-md-6 d-flex justify-content-start align-items-center text-center" data-aos="fade-right">
        <div class="card-step-3">
          <p class="dynapuff-regular px-3 text-dark text-xl">
            <strong class="text-xl">Fill in</strong> your personal details and <span class="text-info text-xl">✅ confirm</span> your booking.
          </p>
        </div>
      </div>
      <div class="col-md-6 d-flex justify-content-end align-items-end text-center" data-aos="fade-left">
        <div class="card-step-4">
          <p class="dynapuff-regular px-3 text-dark text-xl">
            <strong class="text-xl">Check</strong> your email for the <span class="text-warning text-xl">📨 confirmation message</span>.
          </p>
        </div>
      </div>
    </div>
    <a href="/explore-library" class="btn-search-book"><span class="dynapuff-regular text-xl">Search Book 🔎</span></a>
  </div>
</div>

{{-- <div class="custom-shape-divider-bottom-1744618158">
  <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
      <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
      <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
      <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
  </svg>
</div>
<div class="custom-shape-divider-bottom-1744618158">
  <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
      <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
      <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
      <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
  </svg>
</div> --}}

@if ($reminder != null)
  @if (count($reminder) > 0)
    <div class="modal fade" id="modalReminder" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header bg-warning bg-gradient text-dark rounded-top">
            <h5 class="modal-title">
              📚 Book Return Reminder
            </h5>
            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body p-4">
            <div class="row mb-3 align-items-center">
              <div class="col-3 text-center">
                <img src="{{ asset('images/greta-care.png') }}" alt="Reminder" class="img-fluid" style="height: 70px;">
              </div>
              <div class="col-9">
                <div class="alert alert-danger mb-0 py-2 px-3" style="font-size: 0.95rem;">
                  ⚠️ <strong>Oops!</strong> Jangan lupa kembalikan bukunya sebelum batas waktu ya~ 📅
                </div>
              </div>
            </div>

            <div class="list-group">
              @foreach ($reminder as $index => $re)
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                  <div>
                    <span class="badge badge-pill badge-primary mr-2">{{ $index + 1 }}</span>
                    <strong>{{ ucwords($re->book['title']) }}</strong>
                  </div>
                  <span class="badge badge-pill badge-danger">
                    {{ \Carbon\Carbon::parse($re->return_date)->translatedFormat('l, d F Y') }}
                  </span>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
@endif

<div class="modal fade" id="modalCS" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-warning bg-gradient text-dark rounded-top-4 d-flex align-items-center">
        <h5 class="modal-title fw-bold chewy-regular">
          🤝 Need Help? Talk to Us!
        </h5>
        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body p-4">
        <div class="d-flex align-items-center gap-3">
          <div class="flex-shrink-0">
            <img src="{{ asset('images/greta-care.png') }}" alt="Greta Care" class="img-fluid" style="max-height: 100px;">
          </div>
          <div class="flex-grow-1 ms-3">
            <p class="mb-2" style="font-size: 1rem;">
              <strong>Hai! 👋</strong> Jika kamu punya pertanyaan tentang peminjaman atau pengembalian buku, langsung hubungi kami ya!
            </p>
            <div class="border-top pt-2 mt-2">
              <p class="mb-1"><strong class="chewy-regular">📞 Call Center:</strong> <a href="tel:0566523366" class="text-dark">0566-52-3366</a></p>
              <p class="mb-0"><strong class="chewy-regular">📧 Email:</strong> <a href="mailto:cs@greatcrystalschool.sch.id" class="text-dark">cs@greatcrystalschool.sch.id</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="yellow-bg">
  <div class="container text-center py-4">
    <p>&copy; 2025 Great Crystal School and Course Center. All rights reserved.</p>
  </div>
</footer>

<link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@if ($reminder != null)
  @if (count($reminder) > 0)
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        $('#modalReminder').modal('show');
      });
    </script>
  @endif
@endif

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init();

  function toggleMobileNav() {
    document.getElementById('mobileNavOverlay').classList.toggle('show');
  }
</script>

</body>
</html>
