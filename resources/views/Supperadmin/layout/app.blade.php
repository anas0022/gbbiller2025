{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GB BILLER')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


</head>
<style>
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
</style>
<body>
    <div class="preloader">
        <div class="loader">
            <video 
    src="{{ asset('images/preloader/preloader.mp4') }}" 
    autoplay 
    loop 
    muted 
    playsinline
    preload="auto"
    style="width: 100px; height: 100px; display: block; margin: 0 auto;">
</video>

        </div>
    </div>
    

{{-- 
<div class="modal" tabindex="-1" role="dialog" id="time-error-modal" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <video src="{{ asset('images/error/Times.mp4') }}" autoplay loop muted 
               style="width: 100px; height: 100px; display: block; margin: 0 auto;"></video>
        <p style="text-align:center;" id="time-out-msg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="close-modal">OK</button>
      </div>
    </div>
  </div>
</div> --}}
   
      
{{-- 
<script>
let timer;
let logoutTime = 2 * 60 * 1000; // 2 minutes

function resetTimer() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        // Mark that a forced logout is pending so refresh also logs out
        try { sessionStorage.setItem('forceLogout', '1'); } catch (e) { }
        document.getElementById('time-out-msg').textContent = "You have been inactive for 2 minutes. Please confirm to log out.";
        const modalElement = document.getElementById('time-error-modal');
        if (typeof $ !== 'undefined' && typeof $('#time-error-modal').modal === 'function') {
            $('#time-error-modal').modal({ backdrop: 'static', keyboard: false });
            $('#time-error-modal').modal('show');
        } else if (modalElement) {
            modalElement.style.display = 'block';
        }
    }, logoutTime);
}

// Track activity
window.onload = resetTimer;
document.onmousemove = resetTimer;
document.onkeypress = resetTimer;
document.onclick = resetTimer;
document.onscroll = resetTimer;

// Logout on modal confirmation
document.addEventListener('DOMContentLoaded', function () {
    // If a forced logout is pending (e.g., user refreshed), redirect immediately
    try {
        if (sessionStorage.getItem('forceLogout') === '1') {
            sessionStorage.removeItem('forceLogout');
            window.location.replace("{{ route('logout') }}");
            return;
        }
    } catch (e) { }

    var closeBtn = document.getElementById('close-modal');
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            // Use replace to avoid caching the current page in the back-forward cache
            try { sessionStorage.removeItem('forceLogout'); } catch (e) { }
            window.location.replace("{{ route('logout') }}"); // Laravel logout route
        });
    }
});

// If the page is restored from the back-forward cache, force a reload
window.addEventListener('pageshow', function (event) {
    try {
        if (sessionStorage.getItem('forceLogout') === '1') {
            sessionStorage.removeItem('forceLogout');
            window.location.replace("{{ route('logout') }}");
            return;
        }
    } catch (e) { }
    if (event.persisted) {
        window.location.reload();
    }
});

</script> 
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
    (function () {
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, "", window.location.href);
        };
    })();
</script>

</body>
</html> --}}


<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('title', 'GB BILLER')</title>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
<meta name="description" content="This is an example dashboard created using build-in elements and components.">

<meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Pe-icon-7-stroke/1.2.3/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
     <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
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
</style>
</head>
<body>
    <div class="preloader">
        <div class="loader">
            <video 
    src="{{ asset('images/preloader/preloader.mp4') }}" 
    autoplay 
    loop 
    muted 
    playsinline
    preload="auto"
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
    (function () {
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, "", window.location.href);
        };
    })();
</script>
</body>
</html>