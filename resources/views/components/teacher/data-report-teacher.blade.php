@extends('layouts.admin.master')
@section('content')

<style>
   .full-height {
      height: 60vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
   }
   .icon-wrapper i {
      font-size: 200px;
      color: #ccc;
   }
   .icon-wrapper p {
      position: absolute;
      left: 50%;
      transform: translate(-50%, 0%);
      margin: 0;
      font-size: 1.5rem;
      color: black;
      text-align: center;
   }
</style>

@if (sizeof($data['classTeacher']) != 0)
   <div class="container-fluid">      
      {{-- <div class="card card-orange mt-2">
            <div class="card-header">
               <h3 class="card-title">Your Class Teacher</h3>
               <div class="card-tools">
                  <button type="button" class="btn-tool" data-card-widget="collapse" title="Collapse">
                     <i class="fas fa-minus"></i>
                  </button>
               </div>
            </div>
            <div class="card-body p-0">
               <table class="table table-striped dgtojects">
                  <thead>
                        <tr>
                           <th>No</th>
                           <th style="width:15%;">Name</th>
                           <th style="width:85%;">Action</th>
                        </tr>
                  </thead>
                  <tbody>
                  @foreach ($data['classTeacher'] as $dgt)
                     <tr id="{{ 'index_grade_' . $dgt->id }}">
                           <td>
                              {{ $loop->index + 1 }}
                           </td>
                           <td>
                              {{ $dgt->name }} - {{ $dgt->class }}
                           </td>
                           <td>
                              @if (strtolower($dgt->name) === "toddler")
                                 <a class="btn-secondary btn" href="{{ url('teacher/dashboard/report/mid/cardToddler') . '/' . $dgt->id }}">
                                    Mid Report Card
                                 </a>
                                 <a class="btn-primary btn" href="{{ url('teacher/dashboard/report/cardToddler') . '/' . $dgt->id }}">
                                    Report Card
                                 </a>
                              @elseif (strtolower($dgt->name) === "nursery")
                                 <a class="btn-secondary btn" href="{{ url('teacher/dashboard/report/mid/cardNursery') . '/' . $dgt->id }}">
                                    Mid Report Card 
                                 </a>
                                 <a class="btn-primary btn" href="{{url('teacher/dashboard/report/cardNursery') . '/' . $dgt->id }}">
                                    Report Card
                                 </a>
                              @elseif (strtolower($dgt->name) === "kindergarten")
                                 <a class="btn-secondary btn" href="{{ url('teacher/dashboard/report/mid/cardKindergarten') . '/' . $dgt->id }}">
                                    Mid Report Card 
                                 </a>
                                 <a class="btn-primary btn" href="{{ url('teacher/dashboard/report/cardKindergarten') . '/' . $dgt->id }}">
                                    Report Card 
                                 </a>
                              @elseif (strtolower($dgt->name) === "primary")
                                 <a class="btn-success btn"
                                    href="{{url('teacher/dashboard/report') . '/acar/detail/' . $dgt->id}}">
                                    ACAR
                                 </a>
                                 <a class="btn-warning btn"
                                    href="{{url('teacher/dashboard/report') . '/sooa/detail/' . $dgt->id}}">
                                    SOOA
                                 </a>
                                 <a class="btn-warning btn"
                                    href="{{url('teacher/dashboard/report') . '/tcop/detail/' . $dgt->id}}">
                                    TCOP
                                 </a>
                                 @if (session('semester') == 1)
                                 <a class="btn-secondary btn"
                                    href="{{url('teacher/dashboard/report') . '/midcard/semestersatu/' . $dgt->id}}">
                                    Mid Report Card
                                 </a>
                                 <a class="btn-primary btn"
                                    href="{{url('teacher/dashboard/report') . '/card/semestersatu/' . $dgt->id}}">
                                    Report Card
                                 </a>
                                 @elseif (session('semester') == 2)
                                 <a class="btn-secondary btn"
                                    href="{{url('teacher/dashboard/report') . '/midcard/semestersatu/' . $dgt->id}}">
                                    Mid Report Card
                                 </a>   
                                 <a class="btn-primary btn"
                                    href="{{url('teacher/dashboard/report') . '/card/semesterdua/' . $dgt->id}}">
                                    Report Card
                                 </a>   
                                 @endif
                                 
                              @elseif (strtolower($dgt->name) === "secondary")
                                 <a class="btn-success btn"
                                    href="{{url('teacher/dashboard/report') . '/acar/detailSec/' . $dgt->id}}">
                                    ACAR
                                 </a>
                                 <a class="btn-warning btn"
                                    href="{{url('teacher/dashboard/report') . '/sooa/detailSec/' . $dgt->id}}">
                                    SOOA
                                 </a>
                                 <a class="btn-warning btn"
                                    href="{{url('teacher/dashboard/report') . '/tcop/detailSec/' . $dgt->id}}">
                                    TCOP
                                 </a>
                                 @if (session('semester') == 1)
                                    <a class="btn-secondary btn"
                                       href="{{url('teacher/dashboard/report') . '/midcard/semestersatu/' . $dgt->id}}">
                                       Mid Report Card
                                    </a>
                                    <a class="btn-primary btn"
                                       href="{{url('teacher/dashboard/report') . '/cardSec/semestersatu/' . $dgt->id}}">
                                       Report Card 
                                    </a>
                                 @endif
                                 @if (session('semester') == 2)
                                    <a class="btn-secondary btn"
                                       href="{{url('teacher/dashboard/report') . '/midcard/semestersatu/' . $dgt->id}}">
                                       Mid Report Card
                                    </a>
                                    <a class="btn-primary btn"
                                       href="{{url('teacher/dashboard/report') . '/cardSec/semesterdua/' . $dgt->id}}">
                                       Report Card
                                    </a>                              
                                 @endif
                              @endif
                           </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
      </div> --}}
      @foreach ($data['classTeacher'] as $dgt)
         <div class="col-12 mb-3">
            <div class="position-relative p-3 d-flex flex-column shadow-lg border" style="background-color: #ffde9e;border-radius:12px;">
               <div class="ribbon-wrapper ribbon-lg">
               <div class="ribbon bg-dark text-md">
                  {{ $dgt->name }} - {{ $dgt->class }}
               </div>
               </div>
               <p class="flex-grow-1">
                  {{session('name_user')}} |
                  Class Teacher
               </p>
               <div class="flex-wrap p-0">
                  <div class="row">
                     @if (strtolower($dgt->name) === "toddler")
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{ url('teacher/dashboard/report/mid/cardToddler') . '/' . $dgt->id }}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark text-dark">Mid Report Card</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{ url('teacher/dashboard/report/cardToddler') . '/' . $dgt->id }}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark text-dark">Report Card</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                     @elseif (strtolower($dgt->name) === "nursery")
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{ url('teacher/dashboard/report/mid/cardNursery') . '/' . $dgt->id }}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark text-dark">Mid Report Card</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{url('teacher/dashboard/report/cardNursery') . '/' . $dgt->id }}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark text-dark">Report Card</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                     @elseif (strtolower($dgt->name) === "kindergarten")
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{ url('teacher/dashboard/report/mid/cardKindergarten') . '/' . $dgt->id }}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark">Mid Report Card</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{ url('teacher/dashboard/report/cardKindergarten') . '/' . $dgt->id }}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark">Report Card</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                     @elseif (strtolower($dgt->name) === "primary")
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{url('teacher/dashboard/report') . '/acar/detail/' . $dgt->id}}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark">ACAR</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{url('teacher/dashboard/report') . '/tcop/detail/' . $dgt->id}}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark">TCOP</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{url('teacher/dashboard/report') . '/sooa/detail/' . $dgt->id}}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark">SOOA</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                        @if (session('semester') == 1)
                           <div class="col-lg-2 col-md-4 col-6">
                              <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                                 <a  type="button"
                                    href="{{url('teacher/dashboard/report') . '/midcard/semestersatu/' . $dgt->id}}"
                                    class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                                 
                                    <!-- Bagian Utama -->
                                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                          <!-- Ikon -->
                                          <div>
                                             <img src="{{ asset('images/paper.png') }}" 
                                             alt="avatar" class="profileImage img-fluid" 
                                             style="width: 50px; height: 50px; cursor: pointer;">
                                          </div>

                                          <!-- Nama Subject -->
                                          <div class="inner mt-2">
                                             <p class="mb-0 text-lg fw-bold text-center text-dark">Mid Report Card</p>
                                          </div>
                                    </div>
                                 </a>
                              </div>       
                           </div>
                           <div class="col-lg-2 col-md-4 col-6">
                              <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                                 <a  type="button"
                                    href="{{url('teacher/dashboard/report') . '/card/semestersatu/' . $dgt->id}}"
                                    class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                                 
                                    <!-- Bagian Utama -->
                                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                          <!-- Ikon -->
                                          <div>
                                             <img src="{{ asset('images/paper.png') }}" 
                                             alt="avatar" class="profileImage img-fluid" 
                                             style="width: 50px; height: 50px; cursor: pointer;">
                                          </div>

                                          <!-- Nama Subject -->
                                          <div class="inner mt-2">
                                             <p class="mb-0 text-lg fw-bold text-center text-dark">Report Card</p>
                                          </div>
                                    </div>
                                 </a>
                              </div>       
                           </div>
                        @elseif (session('semester') == 2)
                           <div class="col-lg-2 col-md-4 col-6">
                              <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                                 <a  type="button"
                                    href="{{url('teacher/dashboard/report') . '/midcard/semestersatu/' . $dgt->id}}"
                                    class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                                 
                                    <!-- Bagian Utama -->
                                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                          <!-- Ikon -->
                                          <div>
                                             <img src="{{ asset('images/paper.png') }}" 
                                             alt="avatar" class="profileImage img-fluid" 
                                             style="width: 50px; height: 50px; cursor: pointer;">
                                          </div>

                                          <!-- Nama Subject -->
                                          <div class="inner mt-2">
                                             <p class="mb-0 text-lg fw-bold text-center text-dark">Mid Report Card</p>
                                          </div>
                                    </div>
                                 </a>
                              </div>       
                           </div>
                           <div class="col-lg-2 col-md-4 col-6">
                              <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                                 <a  type="button"
                                    href="{{url('teacher/dashboard/report') . '/card/semesterdua/' . $dgt->id}}"
                                    class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                                 
                                    <!-- Bagian Utama -->
                                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                          <!-- Ikon -->
                                          <div>
                                             <img src="{{ asset('images/paper.png') }}" 
                                             alt="avatar" class="profileImage img-fluid" 
                                             style="width: 50px; height: 50px; cursor: pointer;">
                                          </div>

                                          <!-- Nama Subject -->
                                          <div class="inner mt-2">
                                             <p class="mb-0 text-lg fw-bold text-center text-dark">Report Card</p>
                                          </div>
                                    </div>
                                 </a>
                              </div>       
                           </div>
                        @endif
                     @elseif (strtolower($dgt->name) === "secondary")
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{url('teacher/dashboard/report') . '/acar/detailSec/' . $dgt->id}}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark">ACAR</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{url('teacher/dashboard/report') . '/sooa/detailSec/' . $dgt->id}}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark">SOOA</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                           <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                              <a  type="button"
                                 href="{{url('teacher/dashboard/report') . '/tcop/detailSec/' . $dgt->id}}"
                                 class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                              
                                 <!-- Bagian Utama -->
                                 <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                       <!-- Ikon -->
                                       <div>
                                          <img src="{{ asset('images/paper.png') }}" 
                                          alt="avatar" class="profileImage img-fluid" 
                                          style="width: 50px; height: 50px; cursor: pointer;">
                                       </div>

                                       <!-- Nama Subject -->
                                       <div class="inner mt-2">
                                          <p class="mb-0 text-lg fw-bold text-center text-dark">TCOP</p>
                                       </div>
                                 </div>
                              </a>
                           </div>       
                        </div>
                        @if (session('semester') == 1)
                           <div class="col-lg-2 col-md-4 col-6">
                              <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                                 <a  type="button"
                                    href="{{url('teacher/dashboard/report') . '/midcard/semestersatu/' . $dgt->id}}"
                                    class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                                 
                                    <!-- Bagian Utama -->
                                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                          <!-- Ikon -->
                                          <div>
                                             <img src="{{ asset('images/paper.png') }}" 
                                             alt="avatar" class="profileImage img-fluid" 
                                             style="width: 50px; height: 50px; cursor: pointer;">
                                          </div>

                                          <!-- Nama Subject -->
                                          <div class="inner mt-2">
                                             <p class="mb-0 text-lg fw-bold text-center text-dark">Mid Report Card</p>
                                          </div>
                                    </div>
                                 </a>
                              </div>       
                           </div>
                           <div class="col-lg-2 col-md-4 col-6">
                              <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                                 <a  type="button"
                                    href="{{url('teacher/dashboard/report') . '/cardSec/semestersatu/' . $dgt->id}}"
                                    class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                                 
                                    <!-- Bagian Utama -->
                                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                          <!-- Ikon -->
                                          <div>
                                             <img src="{{ asset('images/paper.png') }}" 
                                             alt="avatar" class="profileImage img-fluid" 
                                             style="width: 50px; height: 50px; cursor: pointer;">
                                          </div>

                                          <!-- Nama Subject -->
                                          <div class="inner mt-2">
                                             <p class="mb-0 text-lg fw-bold text-center text-dark">Report Card</p>
                                          </div>
                                    </div>
                                 </a>
                              </div>       
                           </div>
                        @endif
                        @if (session('semester') == 2)
                           <div class="col-lg-2 col-md-4 col-6">
                              <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                                 <a  type="button"
                                    href="{{url('teacher/dashboard/report') . '/midcard/semestersatu/' . $dgt->id}}"
                                    class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                                 
                                    <!-- Bagian Utama -->
                                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                          <!-- Ikon -->
                                          <div>
                                             <img src="{{ asset('images/paper.png') }}" 
                                             alt="avatar" class="profileImage img-fluid" 
                                             style="width: 50px; height: 50px; cursor: pointer;">
                                          </div>

                                          <!-- Nama Subject -->
                                          <div class="inner mt-2">
                                             <p class="mb-0 text-lg fw-bold text-center text-dark">Mid Report Card</p>
                                          </div>
                                    </div>
                                 </a>
                              </div>       
                           </div>
                           <div class="col-lg-2 col-md-4 col-6">
                              <div class="small-box px-2 d-flex flex-column zoom-hover position-relative justify-content-center align-items-center" style="background-color: #ffe8d6;border-radius:12px;">
                                 <a  type="button"
                                    href="{{url('teacher/dashboard/report') . '/cardSec/semesterdua/' . $dgt->id}}"
                                    class="stretched-link d-flex flex-column p-2 text-center h-100 justify-content-center align-items-center">
                                 
                                    <!-- Bagian Utama -->
                                    <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                                          <!-- Ikon -->
                                          <div>
                                             <img src="{{ asset('images/paper.png') }}" 
                                             alt="avatar" class="profileImage img-fluid" 
                                             style="width: 50px; height: 50px; cursor: pointer;">
                                          </div>

                                          <!-- Nama Subject -->
                                          <div class="inner mt-2">
                                             <p class="mb-0 text-lg fw-bold text-center text-dark">Report Card</p>
                                          </div>
                                    </div>
                                 </a>
                              </div>       
                           </div>                         
                        @endif
                     @endif   
                  </div>
               </div> 
            </div>
         </div>
      @endforeach
   </div>
@else
   <div class="container-fluid full-height">
      <div class="icon-wrapper">
         <i class="fa-regular fa-face-laugh-wink"></i>   
         <p>Oops.. <br> This page can only be accessed by class teachers</p>
      </div>
   </div>
@endif
<!-- END TABEL -->

<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
   function saveSemesterToSession() {
      var semesterSelect = document.getElementById('semester');
      var selectedSemester = semesterSelect.value;
      
      // Simpan nilai semester ke dalam session
      $.ajax({
         url: '{{ route('save.semester.session') }}',
         type: 'POST',
         data: {
            semester: selectedSemester,
            _token: '{{ csrf_token() }}'
         },
         success: function(response) {
            console.log('Semester saved to session:', response.semester);
         },
         error: function(xhr, status, error) {
            console.error('Error saving semester to session:', error);
         }
      });
   }
</script>

<script>
   @if(session('swal'))
      Swal.fire({
            icon: '{{ session('swal.type') }}', // 'success', 'error', 'warning', 'info', 'question'
            title: '{{ session('swal.title') }}',
            text: '{{ session('swal.text') }}'
      });
   @endif
</script>
@endsection
