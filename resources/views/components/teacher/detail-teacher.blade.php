@extends('layouts.admin.master')
@section('content')

<div class="container">
  <div class="row">
    <div class="col">
      <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-4">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item">Home</li>
          @if(session('role') == 'admin')
              <li class="breadcrumb-item"><a href="{{url('/admin/teachers')}}">Teacher</a></li>
          @elseif (session('role') == 'teacher')
              <li class="breadcrumb-item"><a href="{{url('/teacher/dashboard/')}}">Teacher</a></li>
          @endif
          <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-4">
      <div class="card mb-4 position-sticky" style="top: 4.5rem">
        <div class="card-body text-center">
          <div class="position-relative d-inline-block" style="width: 150px;">
            <!-- Input file (disembunyikan) -->
            <input type="file" id="profileInput" accept="image/png, image/jpg, image/jpeg" 
              style="display: none;" onchange="previewImage(event)">
        
            <!-- Gambar Profil -->
            <img src="{{ asset('storage/file/profile/'.$data['teacher']->profil) }}" 
                  alt="avatar" class="rounded-circle img-fluid" style="width: 150px;height: 150px; cursor: pointer;"
                  id="profileImage">
        
            <!-- Overlay Edit Text -->
            <div class="position-absolute top-50 start-50 translate-middle text-white bg-dark bg-opacity-50 
                        rounded-circle d-flex align-items-center justify-content-center"
                  style="width: 150px; height: 150px; opacity: 0; transition: opacity 0.3s;"
                  id="editOverlay">
                <span>Edit Profile </span>
            </div>
          </div>
          
          <h5 class="my-3">{{$data['teacher']->name}}</h5>
          <p class="text-muted mb-1">
                              
                {{(date("md", date("U", mktime(0, 0, 0, 
                explode("-", $data['teacher']->date_birth)[2], 
                explode("-", $data['teacher']->date_birth)[1], 
                explode("-", $data['teacher']->date_birth)[0]))) > date("md") 
                ? ((date("Y")-explode("-", $data['teacher']->date_birth)[0])-1)
                :(date("Y")-explode("-", $data['teacher']->date_birth)[0]))
                }} years old

          </p>
          <p class="text-muted mb-4">{{$data['teacher']->home_address}}</p>

          @if (!$data['user'])
            <div class="col-lg">
              <h1 class="badge badge-danger">Don't Have an Account</h1>
              @if (session('role') == 'superadmin')
                <a href="{{url('/superadmin/users/register-user')}}" class="badge badge-primary">Create Account</a>
              @elseif (session('role') == 'admin')  
                <a href="{{url('/admin/users/register-user')}}" class="badge badge-primary">Create Account</a>
              @endif
              
            </div>
          @else
            <div class="col-lg">
              <div class="row justify-content-center">
                <div class="col-sm-4">
                  <p class="mb-0">Username</p>
                </div>
                <div class="col-sm-4">
                  <p class="text-muted">{{$data['user']->username}}</p>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-sm-4">
                  <p class="mb-0">Role</p>
                </div>
                <div class="col-sm-4">
                  <p class="text-muted">{{$data['user']->role_name}}</p>
                </div>
              </div>
              <div class="row justify-content-center">
                <button type="button" class="btn btn-danger" data-toggle="modal"
                    data-target="#{{'changePassword' . $data['user']->id}}">
                    <i class=" fas fa-solid fa-unlock-keyhole"></i>
                    Change password
                </button>
              </div>
            </div> 
          @endif

          
          {{-- <div class="d-flex justify-content-center mb-2">
            <button type="button" class="btn btn-primary">Follow</button>
            <button type="button" class="btn btn-outline-primary ms-1">Message</button>
          </div> --}}
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Full name</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->name}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Unique ID</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->unique_id}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">NIK or Passport</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->nik}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Status</p>
            </div>
            <div class="col-sm-8">
                <p class="text-muted mb-0">
                  @if($data['teacher']->is_active)
                      <h1 class="badge badge-success">Active</h1>
                  @else
                      <h1 class="badge badge-danger">Inactive</h1>
                  @endif
                </p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Gender</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->gender}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Religion</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->religion}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Place of Birth</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->place_birth}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Date of Birth</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{date("d/m/Y", strtotime($data['teacher']->date_birth))}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Nationality</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->nationality}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Last Education</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->last_education}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Major</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->major}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Email</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->email}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Mobile phone</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->handphone}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Temporary Address</p>
            </div>
            <div class="col-sm-8">
              <p class="text-muted mb-0">{{$data['teacher']->temporary_address}}</p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
                <p class="mb-0">Class Teacher</p>
            </div>
            <div class="col-sm-8">
                @if(sizeof($data['teacherGrade']))
                    @foreach($data['teacherGrade'] as $tg)
                      <p class="text-muted mb-0">- {{$tg->name}} - {{$tg->class}}</p>
                    @endforeach
                @else
                  <p class="text-danger mb-0">-</p>
                @endif
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-sm-4">
              <p class="mb-0">Subject Teacher</p>
            </div>
            <div class="col-sm-8">
                @if(sizeof($data['teacherSubject']))
                  @foreach ($data['teacherSubject'] as $ts)
                    <p class="text-muted mb-0">- {{$ts->name_subject}}
                      @if($ts->is_lead) 
                          <span class="badge badge-primary">Main Teacher</span> 
                      @elseif($ts->is_group) 
                          <span class="badge badge-warning">Member</span>
                      @else 
                      @endif
                      ( {{ $ts->name }} - {{ $ts->class }} )
                      </p>  
                  @endforeach
                @else 
                    <a href="{{url('/admin/list')}}"><p class="text-danger mb-0">Subject teacher not ready yet</p></a> 
                @endif
            </div>
            </div>
          </div>
        </div>
      </div>

      
    </div>
  </div>
</div>


<!-- Modal -->
@if ($data['user'] !== null)
<div class="modal fade" id="{{'changePassword' . $data['user']->id}}" data-backdrop="static"
  data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Change password - {{$data['user']->username}}
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <form method="POST" action={{ route('user.changePassword', $data['user']->id) }}
                  enctype="multipart/form-data">
                  @csrf
                  @method('POST')
                  <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input name="password" type="password" class="form-control"
                          id="exampleInputPassword1" aria-describedby="emailHelp">
                      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                  </div>
                  <div class="form-group">
                      <label for="exampleInputPassword2">Reinput password</label>
                      <input name="reinputPassword" type="password" class="form-control"
                          id="exampleInputPassword2">
                  </div>

                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary"
                          data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
@else
@endif

<script>
    const profileImage = document.getElementById('profileImage');
    const profileInput = document.getElementById('profileInput');
    const editOverlay = document.getElementById('editOverlay');

    // Tampilkan overlay saat hover
    profileImage.addEventListener('mouseenter', () => {
        editOverlay.style.opacity = '0';
    });
    editOverlay.addEventListener('mouseenter', () => {
        editOverlay.style.opacity = '0';
    });

    // Sembunyikan overlay saat tidak hover
    profileImage.addEventListener('mouseleave', () => {
        editOverlay.style.opacity = '0';
    });
    editOverlay.addEventListener('mouseleave', () => {
        editOverlay.style.opacity = '0';
    });

    // Klik gambar untuk memilih file
    profileImage.addEventListener('click', () => {
        profileInput.click();
    });

    // Pratinjau gambar saat memilih file
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function () {
          profileImage.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);


      const file = event.target.files[0];

      if (!file) return;

      let formData = new FormData();
      formData.append('file', file);
      formData.append('role', 'teacher');
      formData.append('id', `{{$data['teacher']->id}}`);

      fetch("{{ route('change.profile') }}", {
          method: "POST",
          body: formData,
          headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
          }
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              Swal.fire({
                  icon: 'success',
                  title: data.message,
              });

              // Update gambar tanpa reload
              const reader = new FileReader();
              reader.onload = function(e) {
                  document.getElementById('profileImage').src = e.target.result;
              };
              reader.readAsDataURL(file);
              
              window.location.reload();
          } else {
              Swal.fire({
                  icon: 'error',
                  title: 'Oops... failed to change profile',
              });
          }
      })
      .catch(error => {
          Swal.fire({
              icon: 'error',
              title: 'Oops... something went wrong',
          });
      });
    }
</script>


@if(session('password.success'))
<script>
  setTimeout(() => {
      Swal.fire({
          icon: 'success',
          title: 'Success update password',
      });
  }, 1500);
</script>
@endif

@if(session('after_update_teacher'))
<script>
  setTimeout(() => {
      Swal.fire({
          icon: 'success',
          title: 'Success Edit Data Teacher',
      });
  }, 1500);
</script>
@endif


@endsection