<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Great Crystal-Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('/style.css') }}">
  <link rel="stylesheet" href="{{ asset('template') }}/dist/css/adminlte.min.css">
  <link rel="icon" href="{{ asset('images/greta-face.png') }}" type="image/x-icon">

    
  <style>
    body {
      background-color: #f8f5f0;
      font-family: 'Noto Sans JP', sans-serif;
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
  </style>
</head>
<body>

<!-- Top bar -->
<div class="container-fluid py-2 border-bottom d-none d-md-block">
  <div class="d-flex justify-content-between align-items-center px-3">
    <div>
        <a href="/library-public" class="text-decoration-none text-dark d-flex align-items-center">
          <img src="{{ asset('images/greta-face.png') }}" alt="Logo" style="height: 20px;"> <!-- Ganti dengan logo -->
          <span class="ms-2 fw-bold">Great Crystal School・Library</span>
        </a>
      </div>
      <div class="d-none d-md-block">
        <span class="me-3">Call Center: 0566-52-3366</span>
        <a href="#"><i class="bi bi-facebook me-2"></i></a>
        <a href="#"><i class="bi bi-instagram me-2"></i></a>
        <a href="#"><i class="bi bi-youtube"></i></a>
        @if (session('role') !== null)
        <button onclick="logout()" id="log-out" type="button" class="btn btn-danger btn-xs">Exit</button>
        @endif
      </div>
  </div>
</div>


<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
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
        <li class="nav-item pl-2 boreder-bottom"><a class="nav-link" href="/facility">Facility</a></li>
        <li class="nav-item pl-2 boreder-bottom"><a class="nav-link" href="/article">Artikel</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="mobile-nav-backdrop" id="backdrop" onclick="toggleNavbar()"></div>


<!-- Hero Section -->
<div class="container mt-4">
  <div class="row">
    @foreach ($data as $article)
      <div class="col-6 col-md-2 mb-4">
        <div class="card">
          <div class="card-body p-0">
            <img src="{{asset('images/cover_book.jpg')}}" alt="" class="img-fluid">
            <div class="p-2">
              <h5 class="card-title text-md my-2 text-bold">{{ ucwords($article->title) }}</h5>
              

              @if (session('role') != null)

                {{-- Admin/Library Role --}}
                @if ($isAdmin)
                  @if ($isOutOfStock)
                    <span class="btn btn-sm btn-secondary w-100 disabled">📦 Stock 0</span>
                  @else
                    @if (!$isBooked)
                      <span class="btn btn-sm btn-info w-100">📚 Stock {{ $book->total }}</span>
                    @else
                      <a href="#" class="btn btn-sm btn-success w-100" data-toggle="modal" data-target="#detailBooked-{{ $book->id }}">
                        🔍 View Booked
                      </a>
                    @endif
                  @endif

                {{-- Student/User Role --}}
                @elseif ($isUser)
                  @if (!$isBooked)
                    <a class="btn btn-sm btn-success w-100" 
                      @if ($isGuest)
                        data-toggle="modal" data-target="#modalLogin"
                      @else
                        onclick="showAlert({{ $book->id }})"
                      @endif>
                      📘 Borrow
                    </a>
                  @else
                    <span class="btn btn-sm btn-danger w-100 disabled">❗You Already Booked</span>
                  @endif
                @endif

              @else
                {{-- Guest View --}}
                @if ($isOutOfStock)
                  <a href="#" class="btn btn-sm btn-secondary w-100">
                    📚 Empty
                  </a>
                @else
                  @if ($book->fullBooked() == 1)
                    <a href="#" class="btn btn-sm btn-success w-100" data-toggle="modal" data-target="#detailBooked-{{ $book->id }}">
                      📕 Full Booked
                    </a>
                  @else
                    <a class="btn btn-sm btn-primary w-100" data-toggle="modal" data-target="#modalLogin">
                      📘 Borrow
                    </a>
                  @endif
                @endif
              @endif

            </div>
          </div>
        </div>
      </div>    
      
      <div class="modal fade" id="detailBooked-{{$book->id}}" tabindex="-1" role="dialog" aria-hidden="true">
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
      
    @endforeach

    {{ $data->links() }}

    @if (session('role') == 'student')
      @php
        $reservedBooks = \App\Models\Reserve_book::with(['book'])->where('user_id', session('id_user'))->get();
        @endphp
      @if (count($reservedBooks) != 0)
        <div>
          <p class="text-xl">Detail Borrow Book</p>
          <div class="row">
            @foreach ($reservedBooks as $reservedBook)
            @php
              $countDay = \Carbon\Carbon::parse($reservedBook->return_date)->diffInDays(\Carbon\Carbon::now());
              $countDay = $reservedBook->status == 1 ? $countDay : 0;
              $tooltips = "$countDay days overdue";
            @endphp
              <div class="col-md-2 mb-4">
                <div class="card" tabindex="0" data-bs-toggle="tooltip" title="{{ $tooltips }}">
                  <div class="card-body p-0">
                    <img src="{{asset('images/cover_book.jpg')}}" alt="" class="img-fluid">
                    <div class="p-2">
                      <h5 class="card-title text-md mb-2 text-bold">{{ ucwords($reservedBook->book['title']) }}</h5>
                      {{-- <p class="card-text text-sm">{{ $reservedBook->book['author'] }} | {{ $reservedBook->book['publisher'] }}</p> --}}
                      <p class="card-text text-sm">
                        Pick : {{ \Carbon\Carbon::parse($reservedBook->reserve_date)->format('l, d F Y') }} <br>
                        Borrow : {{ \Carbon\Carbon::parse($reservedBook->return_date)->format('l, d F Y') }} <br>
                        @if ($reservedBook->status == 0)
                          <span class="badge badge-primary">Waiting for pickup</span>
                        @elseif ($reservedBook->status == 1)
                          <span class="badge badge-warning">Current loan period</span>
                        @elseif ($reservedBook->status == 2)
                          <span class="badge badge-success">Returned</span>
                        @endif
                      </p>
                    </div>
                  </div>
                </div>
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
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header bg-success bg-gradient text-white rounded-top">
        <h5 class="modal-title">📖 Borrow Book</h5>
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
            <label for="name">👤 Your Name</label>
            <input name="name" type="text" class="form-control" value="{{ $username }}" readonly>
          </div>

          {{-- Pick-up Date --}}
          <div class="form-group mb-3">
            <label for="date">📅 Pick-up Date</label>
            <input 
              name="pick" 
              type="date" 
              class="form-control" 
              id="pick-date" 
              oninput="setReturnDate(this.value)" 
              required
            >
            <small class="form-text text-muted">
              Maksimal peminjaman: <strong>2 minggu</strong>
            </small>
          </div>

          {{-- Return Date --}}
          <div class="form-group mb-4">
            <label for="date">🗓️ Return Date</label>
            <input 
              name="return" 
              type="date" 
              class="form-control bg-light" 
              id="return-date" 
              readonly
            >
            <small class="form-text text-muted">
              Pengambilan buku bisa dilakukan langsung ke <strong>Perpustakaan GCS Lt. 3</strong>
            </small>
          </div>

          {{-- Submit --}}
          <div class="d-grid">
            <button type="submit" class="btn btn-success btn-block">
              ✅ Submit Request
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login First</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-danger text-md">Login with your student account. If you're external people you can read the book in place</p>
        <form method="POST" action="{{ route('login.library') }}">
          @csrf
          <div class="form-group">
            <label for="name">Username</label>
            <input name="username" type="text" class="form-control">
          </div>

          <div class="form-group">
            <label for="name">Password</label>
            <input name="password" type="text" class="form-control">
          </div>

          <div class="row d-flex justify-content-center">
            <input role="button" type="submit" class="btn btn-success col-12">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- jQuery (wajib) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS (jika pakai Bootstrap modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 (untuk Swal.fire) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
      console.log("Data yang diterima:", data); // Debug data
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

  document.addEventListener("DOMContentLoaded", function() {
    const dateInput = document.getElementById("pick-date");
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    dateInput.min = today;
  });

  
  function toggleNavbar() {
    const nav = document.getElementById('navbarNav');
    const backdrop = document.getElementById('backdrop');
    nav.classList.toggle('show');
  }
</script>



</body>
</html>
