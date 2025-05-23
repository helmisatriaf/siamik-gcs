<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href={{ URL::asset('style.css'); }} >
  <link rel="icon" href="{{ asset('great.png') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{asset('template')}}/dist/css/adminlte.min.css">
  
  <title>SIAMIK Great Crystal School</title>
</head>

<style>
  #mobile {
    display: flex;
    justify-content: center; /* Pusatkan horizontal */
    align-items: center; /* Pusatkan vertikal */
    height: 100vh; /* Pastikan tinggi full layar */
    text-align: start; /* Agar teks di dalam ikut rata tengah */ 
  }

</style>

<body onload="setResponsiveClass()">
  <section id="mobile" class="bg-image justify-content-center align-items-center" style="display: none; height: 100vh;">
    <div class="bg-overlay"></div> <!-- Overlay untuk mengurangi kegelapan gambar -->
    <div class="container-fluid d-flex justify-content-center align-items-center px-4">
        <div class="row d-flex justify-content-center">
            <div class="col-md-12 col-lg-8 col-xl-4">
                <img loading="lazy" src="{{ asset('/images/logo-school.png') }}" class="img-fluid" alt="Sample image">
            </div>

            <div class="col-11 col-md-8 col-lg-2 col-xl-4 text-start pt-4">
                <form method="POST" action="{{ route('actionLogin') }}">
                    @csrf
                    <div class="divider d-flex align-items-center">
                        <p class="text-center text-warning text-bold mx-3 mb-0">SISTEM INFORMATION ACADEMIC</p>
                    </div>

                    <!-- Email input -->
                    <div class="form-outline">
                        <label class="form-label text-white text-start" for="username-mobile">Username</label>
                        <input type="text" id="username-mobile" class="form-control form-control-lg"
                            placeholder="Enter username" name="username" autocomplete="off" value="{{ old('username') }}" />
                    </div>

                    <!-- Password input -->
                    <div class="form-outline">
                        <label class="form-label text-white" for="password-mobile">Password</label>
                        <input type="password" id="password-mobile" class="form-control form-control-lg"
                            placeholder="Enter password" name="password" autocomplete="off" value="{{ old('password') }}" />
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Checkbox -->
                        <div class="form-check mb-0">
                            <input class="form-check-input me-2" type="checkbox" value="" id="remember-mobile" />
                            <label class="form-check-label text-white" for="remember-mobile">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <div class="text-center text-lg-start mt-2">
                        <button type="submit" class="btn btn-warning w-100">Login</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
  </section>


  <section id="desktop" class="vh-100 bg-image">
    <div class="bg-overlay"></div> <!-- Overlay untuk mengurangi kegelapan gambar -->
    <div class="container-fluid justify-content-center align-items-center">
      <div class="row d-flex justify-content-center align-items-center h-custom">
          <div class="col-md-9 col-lg-8 col-xl-5">
            <img loading="lazy" src="{{asset('/images')}}/logo-school.png" class="img-fluid" alt="Sample image"s>
          </div>

          <div class="col-11 col-md-8 col-lg-2 col-xl-4 offset-0 offset-xl-1">
            <form method="POST" action="{{ route('actionLogin') }}">
                @csrf
                <div class="divider d-flex align-items-center">
                    <p class="text-center text-warning mx-3 mb-0">ACADEMIC INFORMATION SYSTEMS</p>
                </div>

                <!-- Email input -->
                <div class="form-outline">
                  <label class="form-label text-white" for="form3Example3">Username</label>
                    <input type="text" id="username-desktop" class="form-control form-control-lg"
                    placeholder="Enter username" name="username" autocomplete="off" value="{{old('username')}}"/>
                </div>

                <!-- Password input -->
                <div class="form-outline">
                  <label class="form-label text-white" for="form3Example4">Password</label>
                    <input type="password" id="password-desktop" class="form-control form-control-lg"
                    placeholder="Enter password" name="password" autocomplete="off" value="{{old('password')}}"/>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                  <!-- Checkbox -->
                  <div class="form-check mb-0">
                    <input class="form-check-input me-2" type="checkbox" value="" id="remember-desktop" />
                    <label class="form-check-label text-white" for="remember-desktop">
                        Remember me
                    </label>
                  </div>
                </div>

                <div class="text-center text-lg-start mt-2">
                  <button type="submit" class="btn btn-warning w-100" style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                </div>

            </form>
          </div>
      </div>
    </div>
  </section>

  <link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

  <script>
    function setResponsiveClass() {
      if (window.innerWidth < 768) {
        document.getElementById('mobile').style.display = 'block';
        document.getElementById('mobile').classList.add('d-flex');
        document.getElementById('desktop').style.display = 'none';
      } else {
        document.getElementById('mobile').style.display = 'none';
        document.getElementById('desktop').style.display = 'block';
      }
    }
  </script>

  @if ($errors->any())
    @if($errors->first('invalid'))
      <script>
        Swal.fire({
          icon: "error",
          title: "Invalid username or password",
          text: "Make sure your input is correctly !!!"
        });
      </script>
    @endif
    
    @if($errors->first('credentials'))
      <script>
        Swal.fire({
          icon: "error",
          title: "Invalid credentials",
          text: "Make sure you login first !!!"
        });      
      </script>
    @endif

    @if ($errors->first('username'))
    <script>
        Swal.fire({
          icon: "error",
          title: "Username is required !!!",
        });
    </script>
    @elseif ($errors->first('password'))
    <script>
      Swal.fire({
        icon: "error",
        title: "Password is required !!!",
      });
  </script>
    @endif
  @endif

  @if(session('success.update.password'))
   <script>
      Swal.fire({
        icon: 'success',
        title: 'Successfully',
        text: 'Success update password, please login again !!!',
      });
    </script>
   @endif

</body>

</html>
