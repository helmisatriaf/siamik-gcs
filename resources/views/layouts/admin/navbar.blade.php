<style>
  .navbar-center {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      font-weight: bold;
      color: black;
  }
  .shake {
      animation: shake 1.5s;
      animation-iteration-count: infinite;
  }
  @keyframes shake {
      0% { transform: translateX(-1px); }
      25% { transform: translateX(1px); }
      50% { transform: translateX(-1px); }
      75% { transform: translateX(1px); }
      100% { transform: translateX(-1px); }
  }

  /* .streak-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    padding: 6px 14px;
    height: 36px;

    text-decoration: none;
    font-weight: 600;

    margin-top: 0px;
    padding: 4px;
    width: 72px;
    height: 32px;
    background-color: #ff3d00;
    color: white;
    clip-path: polygon(
    10% 0%, 20% 10%, 35% 5%, 50% 15%, 65% 5%, 80% 10%, 90% 0%, 
    100% 20%, 95% 35%, 100% 50%, 95% 65%, 100% 80%, 90% 100%, 
    75% 90%, 60% 95%, 50% 85%, 40% 95%, 25% 90%, 10% 100%, 
    0% 80%, 5% 65%, 0% 50%, 5% 35%, 0% 20%
    );
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(255, 80, 0, 0.4);
  }

  /* hover */
  /* .streak-nav:hover {
    transform: scale(1.2) rotate(-3deg);
    color: #e47600;
    animation: bounce 0.3s ease-in-out;
  }  */

  /* .streak-nav {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 2px 4px;
    border-radius: 20px;
    background: linear-gradient(135deg, #ff9a00, #ff3d00);
    color: white;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 4px 10px rgba(255, 80, 0, 0.4);
    transition: all 0.3s ease;
  } */

/* hover */
/* .streak-nav:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 6px 16px rgba(255, 80, 0, 0.6);
} */
  

  /* icon animasi */
  /* .streak-icon {
      font-size: 18px;
      animation: flame 1.5s infinite;
  } */

  /* angka */
  /* .streak-count {
      font-size: 14px;
  } */

  /* animasi api */
  /* @keyframes flame {
      0% { transform: scale(1); }
      50% { transform: scale(1.3) rotate(5deg); }
      100% { transform: scale(1); }
  } */
</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #fff3c0;">
  <!-- Left navbar links -->
  <ul class="navbar-nav" id="btn-custom-suzyan">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block shake">
      <a class="dynapuff-regular hero-title">System Academic Periode {{ session('semester') }} Year {{ session('academic_year') }}</a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    @if (session('role') == 'parent' || session('role') == 'admin' || session('role') == 'superadmin')
    <li class="nav-item">
      <a class="nav-link"
        @if (session('role') == 'superadmin' || session('role') == 'admin')
          href="/cc"
        @elseif (session('role') == 'parent' )
          href="/customer-service"
        @endif 
      >
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge" id="notif-message"></span>
      </a>
    </li>
    @endif

    {{-- @if (session('role') == 'student')
      <li class="nav-item">
        <a href="{{ url('student/streak') }}" class="streak-nav">
            <span class="streak-icon">🔥</span>
            <span class="streak-count">
                {{ $streak->current_streak ?? 0 }}
            </span>

        </a>
      </li>
    @endif --}}

    <li class="nav-item">
      <button href="javascript:void(0)" id="log-out" type="button" class="btn-menu-top">Exit</button>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>

<script>
  var element = document.body;

  if(!localStorage.getItem("sidebar") || localStorage.getItem("sidebar") == 'close'){
    element.classList.add('sidebar-collapse');
  } else {
    element.classList.remove('sidebar-collapse');
  }

  document.getElementById("btn-custom-suzyan").addEventListener("click", function () {
  
    if(localStorage.getItem("sidebar") == 'close'){
      localStorage.setItem("sidebar", "open");
    } else {
      localStorage.setItem("sidebar", "close");
    }
  });
</script>

{{-- @if (session('role') == 'parent' || session('role') == 'admin' || session('role') == 'superadmin')
  <script>
    $(document).ready(function () {
      function notificationMessages() {
        $.ajax({
          url: '/get-notification', // Endpoint untuk mengambil pesan terbaru
          method: 'GET',
          success: function (response) {
            $('#notif-message').text(response);
          }
        });
      }
      setInterval(notificationMessages, 2000);
    });
  </script>
@endif --}}