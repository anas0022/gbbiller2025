<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bill Management System</title>
    <link rel="stylesheet" href="{{ asset('css/Login/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="background-animation"></div>
        <div class="login-container">
            <div class="login-card">
                <div class="logo-section">
                    <div class="logo-container">
                        <img src="http://127.0.0.1:8000/images/Group 11(3).png" alt="Logo" class="logo" style="filter:invert(1);">
                    </div>

                </div>

                {{-- General Errors --}}
                <div id="general-errors" class="error-message general-error"></div>

                <form id="loginForm" action="{{ route('login') }}" method="POST" class="login-form">
                    @csrf

                    {{-- Biller Code --}}
                    <div class="input-group">
                        <div class="input-container">
                            <span class="input-icon">
                                <ion-icon name="business"></ion-icon>
                            </span>
                            <input id="numOnly" name="biller_code" type="text" class="form-input" placeholder=" ">
                            <label class="input-label">Biller Code</label>
                        </div>
                        <span class="error-message" id="error-biller_code"></span>
                    </div>

                    {{-- Password --}}
                    <div class="input-group">
                        <div class="input-container">
                            <span class="input-icon">
                                <ion-icon name="lock-closed"></ion-icon>
                            </span>
                            <input id="password" name="password" type="password" class="form-input" placeholder=" " minlength="6">
                            <label class="input-label">Password</label>
                            <span class="password-toggle" onclick="togglePassword()">
                                <ion-icon name="eye" id="eye-icon"></ion-icon>
                            </span>
                        </div>
                        <span class="error-message" id="error-password"></span>
                    </div>

                    {{-- Forgot Password --}}
                    <div class="forgot-password">
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="login-btn">
                        <span class="btn-text">Sign In</span>
                        <div class="btn-loader"></div>
                    </button>
                </form>
            </div>
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
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('name', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('name', 'eye');
            }
        }

        // Form submission with loading state
        document.getElementById("loginForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            const submitBtn = document.querySelector('.login-btn');
            const btnText = document.querySelector('.btn-text');
            const btnLoader = document.querySelector('.btn-loader');

            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            // Clear previous errors
            document.getElementById("general-errors").innerText = "";
            document.getElementById("general-errors").classList.remove('show');
            
            // Clear field-specific errors
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(el => {
                el.innerText = "";
                el.classList.remove('show');
            });

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
                        // Handle field-specific errors
                        for (const key in data.errors) {
                            const errorSpan = document.getElementById(`error-${key}`);
                            if (errorSpan) {
                                errorSpan.innerText = data.errors[key][0];
                                errorSpan.classList.add('show');
                            }
                        }
                    } else if (data.message) {
                        // General error
                        const generalError = document.getElementById("general-errors");
                        generalError.innerText = data.message;
                        generalError.classList.add('show');
                    }
                } else {
                    // Success: redirect
                    window.location.href = data.redirect;
                }

            } catch (err) {
                const generalError = document.getElementById("general-errors");
                generalError.innerText = "Something went wrong. Please try again.";
                generalError.classList.add('show');
            } finally {
                // Hide loading state
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-input');

            function updateInputState(input) {
                const label = input.nextElementSibling;
                if (input.value && input.value.trim() !== '') {
                    label.style.transform = 'translateY(-24px) scale(0.85)';
                    label.style.color = '#667eea';
                    label.style.background = '#ffffff';
                    label.style.padding = '0 8px';
                } else {
                    label.style.transform = 'translateY(-50%)';
                    label.style.color = '#9ca3af';
                    label.style.background = 'transparent';
                    label.style.padding = '0';
                }
            }

            inputs.forEach(function(input) {
                ['input', 'change', 'blur', 'focus'].forEach(function(evt) {
                    input.addEventListener(evt, function() {
                        updateInputState(input);
                    });
                });
                // Initialize state on load
                updateInputState(input);
            });

            // Handle delayed autofill
            setTimeout(function() {
                inputs.forEach(updateInputState);
            }, 400);

            // Add focus/blur effects
            inputs.forEach(function(input) {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-1px)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>

</html>
