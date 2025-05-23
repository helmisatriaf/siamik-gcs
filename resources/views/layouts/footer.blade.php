<footer class="main-footer text-sm">
  <strong>Copyright &copy; {{ date('Y') }} <a target="_blank" href="https://great.sch.id/">Great Crystal School & Course Center</a>.</strong>
  {{-- All rights reserved. --}}
  <b>Version</b> 1.0
   <div class="float-right">
   </div>
</footer>


 <!-- Control Sidebar -->
 <aside class="control-sidebar control-sidebar-dark">
   <!-- Control sidebar content goes here -->
 </aside>
 <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('template')}}/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('template')}}/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
 $.widget.bridge('uibutton', $.ui.button)
</script>


<!-- Bootstrap 4 -->
<script src="{{asset('template')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Select2 -->
<script src="{{asset('template')}}/plugins/select2/js/select2.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Bootstrap Switch -->
<script src="{{asset('template')}}/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<!-- AdminLTE App -->
<script src="{{asset('template')}}/dist/js/adminlte.min.js"></script>

<!-- FullCalendar JS -->
<script src="{{ asset('template/plugins/fullcalendar/main.min.js') }}"></script>

{{-- PDF.js --}}
<script type="module">
  import pdfjsDist from 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.10.38/+esm'
</script>

<!-- SweetAlert -->
<script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
  $('.js-select2').select2({
    closeOnSelect : false,
    placeholder : "Click to select an option",
    theme: 'bootstrap4',
    allowHtml: true,
    allowClear: true,
    tags: true,
    searchInputPlaceholder: 'Search options'
  }); 
 </script>
<script src="{{ asset('js/logout.js') }}" defer></script>

