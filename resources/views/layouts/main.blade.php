<!DOCTYPE html>
<html lang="{{ str_replace('_', '_', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{ config('app.name', 'Changa App') }}</title>

    <!-- loader-->

    <link href="{{ asset('css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/pace.min.js') }}"></script>
    <!--favicon-->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <!-- Vector CSS -->
    {{-- <link href="{{asset('plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/> --}}
    <!-- simplebar CSS-->
    <link href="{{ asset('plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <!-- Bootstrap core CSS-->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <!-- animate CSS-->
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons CSS-->
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sidebar CSS-->
    <link href="{{ asset('css/sidebar-menu.css') }}" rel="stylesheet" />
    <!-- Custom Style-->
    <link href="{{ asset('css/app-style.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/35963515c4.js" crossorigin="anonymous"></script>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.css" />
    {{-- @extends('panels/styles') --}}
    <style>
        textarea {
            height: 200px !important;
        }

        .multiselect-native-select .btn-group, .multiselect-container.dropdown-menu.show{
            width: 100%;
        }

        .multiselect-native-select .btn-group button{
            text-align: left !important;
        }

        li::marker {
            content: '' !important;
        }
    </style>
</head>

<body class="bg-theme bg-theme1">

    @yield('content')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- simplebar js -->
    <script src="{{ asset('plugins/simplebar/js/simplebar.js') }}"></script>
    <!-- sidebar-menu js -->
    <script src="{{ asset('js/sidebar-menu.js') }}"></script>
    <!-- loader scripts -->
    {{-- <script src="{{asset('js/jquery.loading-indicator.js')}}"></script> --}}
    <!-- Custom scripts -->
    <script src="{{ asset('js/app-script.js') }}"></script>
    <!-- Chart js -->

    <script src="{{ asset('plugins/Chart.js/Chart.min.js') }}"></script>

    <!-- Index js -->
    <script src="{{ asset('js/index.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.js"></script>
    {{-- <script src="//cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script> --}}
    <script src="{{ asset('js/commonFunction.js') }}"></script>

    <script>
        $('#tags').multiselect();
        // CKEDITOR.replace('description');
        // $('#description').ckeditor();
    </script>

    @yield('scripts')
</body>

</html>
