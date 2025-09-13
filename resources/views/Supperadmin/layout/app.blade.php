<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('title', 'GB BILLER')</title>

    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">

    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Pe-icon-7-stroke/1.2.3/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>


    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .gradient-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100% !important;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
        }

        .loader video {
            width: 150px;
            height: 150px;
        }

        .preloader.fade-out {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .vertical-nav-menu i.metismenu-state-icon {

            font-size: 20px;
        }

        .modal-backdrop {
            background-color: #0000;
        }

        .modal-dialog {
            box-shadow: 0 .76875rem 2.4875rem rgba(0, 106, 213, 0.3), 0 1.3375rem 1.70625rem rgba(52, 58, 64, .3), 0 .55rem .53125rem rgba(159, 0, 0, 0.05), 0 .225rem .4375rem rgba(0, 128, 255, 0.3);
            border-radius: .25rem;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
   @stack('modals')
        @stack('scripts')
<body>
    <div class="preloader">
        <div class="loader">
            <video src="{{ asset('images/preloader/preloader.mp4') }}" autoplay loop muted playsinline preload="auto"
                style="width: 100px; height: 100px; display: block; margin: 0 auto;">
            </video>

        </div>
    </div>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        @include('Supperadmin.layout.header')
        <div class="app-main">
            @include('Supperadmin.layout.sidebar')

            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('content')
                </div>



                @include('Supperadmin.layout.footer')
            </div>
        </div>
        <script>
            let timer;
            let logoutTime = 2 * 60 * 1000; // 2 minutes in milliseconds

            function resetTimer() {
                clearTimeout(timer);
                timer = setTimeout(() => {

                    window.location.href = "{{ route('logout') }}"; // Laravel logout route
                }, logoutTime);
            }

            // Track activity
            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            document.onclick = resetTimer;
            document.onscroll = resetTimer;

            window.addEventListener('load', function() {
                var preloader = document.querySelector('.preloader');
                preloader.classList.add('fade-out');

                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500); // match the CSS transition duration
            });
        </script>
        <script>
            (function() {
                window.history.pushState(null, "", window.location.href);
                window.onpopstate = function() {
                    window.history.pushState(null, "", window.location.href);
                };
            })();
        </script>
     
</body>

</html>
