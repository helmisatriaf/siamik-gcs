<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Great Crystal School</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="role" content="{{ session('role') }}">
<link rel="icon" href="{{ asset('great.png') }}" type="image/x-icon">

<!-- Google Font: Source Sans Pro -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat+Brush&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('fontawesome')}}/css/all.min.css">
<link rel="stylesheet" href="{{asset('/style.css')}}">
<script src="{{asset('template')}}/plugins/jquery/jquery.min.js" referrerpolicy="no-referrer"></script>

<!-- Theme style -->
<link rel="stylesheet" href="{{asset('template')}}/dist/css/adminlte.min.css">

<!-- Multi Select Javacript -->
<link rel="stylesheet"  href="{{asset('template')}}/plugins/select2/css/select2.min.css">
<link rel="stylesheet"  href="{{asset('template')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  
<!-- SweetAlert -->
<link rel="stylesheet" href="{{asset('template')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

<!-- FullCalendar CSS -->
<link rel="stylesheet" href="{{asset('template')}}/plugins/fullcalendar/main.min.css">

{{-- PDF.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.12.313/pdf.min.js"></script>

{{-- FROALA --}}
<link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>

{{-- MOMENT --}}
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
