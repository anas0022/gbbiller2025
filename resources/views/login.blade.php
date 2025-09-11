

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="{{ asset('css/Login/login.css') }}">


</head>
<body>
<div class="container">
  <div class="login-box">
    <img src="{{ asset('images/Group 11(3).png')}}" alt="Logo" class="avatar">

    {{-- General Errors --}}
    <div id="general-errors" style="color: red; margin-bottom: 10px;"></div>

    <form id="loginForm" action="{{ route('login') }}" method="POST">
      @csrf

      {{-- Biller Code --}}
      <div class="input-box">
        <span class="icon"><ion-icon name="mail"></ion-icon></span>
        <input 
          id="numOnly" 
          name="biller_code" 
          type="text" 
         
        
          required 
           
         
        >
        <label>Biller Code</label>
        <span class="error-message" id="error-biller_code" style="color:red; font-size:0.9em;"></span>
      </div>

      {{-- Password --}}
      <div class="input-box">
        <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
        <input 
          id="password" 
          name="password" 
          type="password" 
          required 
          minlength="6"
        >
        <label>Password</label>
        <span class="error-message" id="error-password" style="color:red; font-size:0.9em;"></span>
      </div>

      {{-- Forgot Password --}}
      <div class="remember-forgot">
        <a href="#">Forgot Password?</a>
      </div>

      {{-- Submit --}}
      <button type="submit" class="btn">Login</button>
    </form>
  </div>
</div>

  <!-- Ionicons -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  
{{-- <script>
const inp = document.getElementById('numOnly');

// Remove any non-digit characters as the user types
inp.addEventListener('input', (e) => {
  const cleaned = e.target.value.replace(/\D+/g, ''); // keep digits only
  if (cleaned !== e.target.value) {
    e.target.value = cleaned;
  }
});

// Prevent nondigit keys (allows navigation keys)
inp.addEventListener('keydown', (e) => {
  // Allow: backspace, del, arrows, home, end, tab
  const allowed = ['Backspace','Delete','ArrowLeft','ArrowRight','Home','End','Tab'];
  if (allowed.includes(e.key)) return;
  // Block if not digit
  if (!/^\d$/.test(e.key)) e.preventDefault();
});

// Prevent non-digit paste
inp.addEventListener('paste', (e) => {
  const paste = (e.clipboardData || window.clipboardData).getData('text');
  if (/\D/.test(paste)) {
    e.preventDefault();
    // Optionally insert cleaned digits:
    const cleaned = paste.replace(/\D+/g, '');
    if (cleaned) {
      // insert at cursor position
      const { selectionStart: start, selectionEnd: end } = inp;
      const val = inp.value;
      inp.value = val.slice(0, start) + cleaned + val.slice(end);
      inp.setSelectionRange(start + cleaned.length, start + cleaned.length);
    }
  }
});
</script> --}}

<script>
document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    // Clear previous errors
    document.getElementById("general-errors").innerText = "";
    document.getElementById("error-biller_code").innerText = "";
    document.getElementById("error-password").innerText = "";

    const formData = new FormData(this);

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            if (data.errors) {
                // Field-specific errors
                for (const key in data.errors) {
                    const errorSpan = document.getElementById(`error-${key}`);
                    if (errorSpan) errorSpan.innerText = data.errors[key][0];
                }
            } else if (data.message) {
                // General error
                document.getElementById("general-errors").innerText = data.message;
            }
        } else {
            // Success: redirect
            window.location.href = data.redirect;
        }

    } catch (err) {
        document.getElementById("general-errors").innerText = "Something went wrong. Please try again.";
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.input-box input');

    function updateHasValueClass(input) {
        if (input.value && input.value.trim() !== '') {
            input.classList.add('has-value');
        } else {
            input.classList.remove('has-value');
        }
    }

    inputs.forEach(function (input) {
        ['input','change','blur'].forEach(function (evt) {
            input.addEventListener(evt, function () { updateHasValueClass(input); });
        });
        // Initialize state on load
        updateHasValueClass(input);
    });

    // Handle delayed autofill
    setTimeout(function(){ inputs.forEach(updateHasValueClass); }, 400);
});
</script>
</body>
</html>
