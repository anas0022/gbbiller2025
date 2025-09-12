<form action="{{ route('superadmin.menus.store') }}" method="POST" enctype="multipart/form-data" id="moduleForm">
    @csrf
    <input type="hidden" name="if" value="">
    <span id="success-span" style="color: green; margin-top:10px; display:block;"></span>

    <span id="general-errors" style="color: red; margin-bottom: 10px;"></span>
    <div class="form-group">
        <label for="module_name">Module Name</label>
        <input type="text" id="module_name" name="modulename" class="form-control">
        <span class="text-danger error-text modulename_error" id="modulename_error"></span>
    </div>

    <div class="form-group">
        <label for="icon">Icon</label>
        <input type="text" id="icon" name="icon" class="form-control" placeholder="e.g. fa fa-id-card">
        <span class="text-danger error-text icon_error" id="icon_error"></span>
        <p style="margin-top:10px;">
            Preview: <span id="icon-preview"></span>
            <small id="icon-error" style="color:red; display:none;">Invalid fa-icon class</small>
        </p>
    </div>

    <button type="submit" class="btn btn-primary" >Create Module</button>
    <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
</form>


<script>
  
// Font Awesome icon preview
document.getElementById('icon').addEventListener('input', function() {
    const preview = document.getElementById('icon-preview');
    const errorMsg = document.getElementById('icon-error');
    const className = this.value.trim();

    preview.innerHTML = `<i class="${className}"></i>`;
    const iconEl = preview.querySelector("i");
    const style = window.getComputedStyle(iconEl, '::before');
    const hasIcon = style && style.content !== 'none' && style.content !== '';

    if (hasIcon) {
        errorMsg.style.display = "none";
    } else {
        errorMsg.style.display = "inline";
    }
});

</script>
  <script>
      document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("moduleForm").addEventListener("submit", async function(e) {
        e.preventDefault(); // stop normal submit

        // Clear errors + success
        document.getElementById("modulename_error").innerText = "";
        document.getElementById("icon_error").innerText = "";
        document.getElementById("general-errors").innerText = "";
        document.getElementById("success-span").innerText = "";

        const formData = new FormData(this);

        try {
            const response = await fetch(this.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    for (const key in data.errors) {
                        const errorSpan = document.getElementById(`${key}_error`);
                        if (errorSpan) errorSpan.innerText = data.errors[key][0];
                    }
                } else if (data.message) {
                    document.getElementById("general-errors").innerText = data.message;
                }
            } else {
                // ✅ Show success message
              document.getElementById("success-span").innerHTML = 
    (data.message || "Success!") + 
    '<img src="/images/success/icons/check-mark.png" style="width:20px; margin-left:10px;" />';

                // Optional: clear form after success
                this.reset();

                // Optional: redirect if API sends redirect URL
                if (data.redirect) {
                    setTimeout(() => window.location.href = data.redirect, 1500);
                }
            }

        } catch (err) {
            document.getElementById("general-errors").innerText =
                "Something went wrong. Please try again.";
        }
    });
});


    </script>
    