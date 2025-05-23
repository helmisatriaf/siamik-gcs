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
</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav" id="btn-custom-suzyan">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block shake">
      <a class="nav-link text-black text-md">System Academic Periode {{ session('semester') }} Year {{ session('academic_year') }}</a>
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
    <li class="nav-item">
      <button href="javascript:void(0)" id="log-out" type="button" class="btn btn-danger">Exit</button>
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