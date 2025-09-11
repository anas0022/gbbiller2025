<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
 

</head>
<body>
    
    <style>
    .hamburger-inner, .hamburger-inner::before, .hamburger-inner::after {
            background: #fff !important;
        }
        </style>

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
</div>
   
       @include('Supperadmin.layout.header')

    @include('Supperadmin.layout.sidebar')

    <div class="app-main__outer">
        <div class="app-main__inner">
        @yield('content')
      
        </div>
    </div> 
 

 
    @include('Supperadmin.layout.footer')

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


</body>
</html>