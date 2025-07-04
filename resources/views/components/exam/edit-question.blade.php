@extends('layouts.admin.master')
@section('content')

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col">
            <nav aria-label="breadcrumb" class="rounded-3 p-3" style="background-color: #ffde9e;">
                  <ol class="breadcrumb mb-0" style="background-color: #fff3c0;">
                     <li class="breadcrumb-item">Home</li>
                     @if (session('role') == 'teacher')
                     <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/dashboard/exam/teacher')}}">Assessments</a></li>
                     @else
                     <li class="breadcrumb-item"><a href="{{url('/' .session('role'). '/exams')}}">Assessments</a></li>
                     @endif
                     <li class="breadcrumb-item active" aria-current="page">Edit Question</li>
                  </ol>
            </nav>
         </div>
      </div>

      <div class="row d-flex justify-content-center mt-3">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div>
                  <form method="POST" action="{{route('action.update.question', ['id' => $data->id])}}" class="grid">
                  @csrf
                  <h3>Edit Question Assessment {{$data->name_exam}}</h3>
                  <input type="text" name="model" value="{{$data->model}}" hidden>
                  <div class="row">
                     @foreach ($data->question as $index => $question)
                        <div class="card col-12 p-4">
                              @if ($question->type == "mc")
                                 <h4>Multiple Choice Questions</h4>
                                 <label>Question {{$index + 1}}</label>
                                 <textarea class="summernote" name="question_mc[{{$index}}][question]">{{$question->text}}</textarea>
                                 <div class="mt-2">
                                    <label>Options:</label>
                                    @foreach ($question->answer as $answer)
                                       <input type="text" class="form-control mb-2" name="question_mc[{{$index}}][answer][{{$answer->id}}]" value="{{$answer->answer_text}}" placeholder="Option" required>
                                    @endforeach
                                 </div>

                                 <label class="mt-2">Correct Answer:</label>
                                 <select name="question_mc[{{$index}}][question_key]" class="form-control" required>
                                    @foreach ($question->answer as $array => $correct)
                                       @php
                                          $abjad = ['A', 'B', 'C', 'D'];
                                       @endphp

                                       <option value="{{$correct->id}}" {{$correct->is_correct ? 'selected' : ''}}>{{$abjad[$array]}}</option>
                                    @endforeach
                                 </select>
                                 
                                 <input type="number" name="question_mc[{{$index}}][question_id]" value="{{$question->id}}" hidden>
                                

                              @elseif ($question->type == "essay")
                                 <h4>Essay Questions</h4>
                                 <label>Question {{$index + 1}}</label>
                                 <textarea class="summernote" name="essay[{{$index}}][question]">{{$question->text}}</textarea>
                                 <label class="mt-2">Correct Answer:</label>
                                 @foreach ($question->answer as $answer)
                                    <input type="text" name="essay[{{$index}}][answer]" value="{{$answer->answer_text}}" class="form-control" placeholder="Enter correct answer">
                                 @endforeach
                                 <input type="number" name="essay[{{$index}}][question_id]" value="{{$question->id}}" hidden>
                              @endif
                        </div>
                     @endforeach
                  </div>

                  <div class="justify-content-center">
                     <input role="button" type="submit" class="btn btn-success center w-100">
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>

<script src={{asset('assets/vendors/summernote/summernote-lite.min.js')}}></script>

<script src="{{asset('template')}}/plugins/sweetalert2/sweetalert2.min.js"></script>
@if(session('success_edit_question')) 
    <script>
        Swal.fire({
        icon: 'success',
        title: 'Successfully',
        text: 'Successfully Edit Question',
        });
    </script>
@endif

<script>
    $('.summernote').summernote({
         tabsize: 2,
         height: 400,
         callbacks: {
            onImageUpload: function(files) {
               for (let i = 0; i < files.length; i++) {
                  uploadImage(files[i], this);
               }
            }
         }
      });

      $("#hint").summernote({
         height: 100,
         toolbar: false,
         placeholder: 'type with apple, orange, watermelon and lemon',
         hint: {
            words: ['apple', 'orange', 'watermelon', 'lemon'],
            match: /\b(\w{1,})$/,
            search: function(keyword, callback) {
               callback($.grep(this.words, function(item) {
                  return item.indexOf(keyword) === 0;
               }));
            }
         }
      });

      function uploadImage(file, editor) {
         let data = new FormData();
         data.append("file", file);
         data.append("_token", $('meta[name="csrf-token"]').attr('content'));

         $.ajax({
            url: '/upload-image-question',
            method: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(resp) {
               $(editor).summernote('insertImage', resp.link);
            },
            error: function(err) {
               alert("Upload gagal");
               console.error(err);
            }
         });
      }

</script>

@endsection
