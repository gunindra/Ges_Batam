<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  {{-- <link href="img/logo/logo.png" rel="icon"> --}}
  <title>@yield('title')</title>
  <link href="{{ asset('RuangAdmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('RuangAdmin/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('RuangAdmin/css/ruang-admin.min.css') }}" rel="stylesheet">
  <link href="{{ asset('RuangAdmin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link href="{{ asset('RuangAdmin/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('RuangAdmin/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" >
  <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
  <link rel="stylesheet" href="{{ asset('css/flatpickr.css') }}">
  <link rel="stylesheet" href="{{ asset('css/monthSelect.css') }}">

</head>

<body id="page-top">

    @yield('content')

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


  <script src="{{ asset('RuangAdmin/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('RuangAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('RuangAdmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  <script src="{{ asset('RuangAdmin/js/ruang-admin.min.js') }}"></script>
  <script src="{{ asset('RuangAdmin/vendor/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('RuangAdmin/js/demo/chart-area-demo.js') }}"></script>
  <script src="{{ asset('RuangAdmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('RuangAdmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script src=" {{ asset('RuangAdmin/vendor/select2/dist/js/select2.min.js') }}"></script>
  <script src=" {{ asset('RuangAdmin/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/sweetalert2.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/flatpickr.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/monthSelect.js') }}"></script>
  <script>
      function showMessage(type, message) {

          if (!type || type === '' || !message || message === '') {
              return;
          }

          return Swal.fire({
              icon: type,
              title: message,
              showConfirmButton: false,
              timer: 2000
          })

      }
  </script>

  @yield('script')
</body>

</html>
