<form action="{{ route('superadmin.subscription.store') }}" method="POST" enctype="multipart/form-data" id="subform">
    @csrf
    <input type="hidden" name="id" value="" id="subid">
    <span id="success-span" style="color: green; margin-top:10px; display:block;"></span>

    <span id="general-errors" style="color: red; margin-bottom: 10px;"></span>
    <div class="form-group">
        <label for="sub">Store Name</label>
        <input type="text" id="Store" name="Store" class="form-control">
        <span class="text-danger error-text Store_error" id="Store_error"></span>
    </div>

   <!--  <div class="form-group">
        <label for="sub">Subscription</label>
        <select type="text" id="Sub_type" name="Sub_type" class="form-control">
            <option value="">
                -select subscripi
            </option>
        </select>
        <span class="text-danger error-text Sub_type_error" id="Sub_type_error"></span>
    </div> -->

    <div class="form-group">
    <label for="logo">Logo</label>
    <input type="file" id="logo" name="logo" class="form-control" 
           accept="image/*" onchange="previewLogo(event)">
    <span class="text-danger error-text icon_error" id="icon_error"></span>

    <!-- Preview container -->
    <div id="logoPreviewContainer" style="margin-top:10px; display:none;">
        <img id="logoPreview" src="" alt="Logo Preview" 
             style="max-width: 150px; max-height: 150px; border:1px solid #ccc; border-radius:8px;"/>
    </div>
</div>

<script>
function previewLogo(event) {
    const file = event.target.files[0];
    const errorEl = document.getElementById("icon_error");
    const previewContainer = document.getElementById("logoPreviewContainer");
    const previewImg = document.getElementById("logoPreview");

    // Reset
    errorEl.textContent = "";
    previewContainer.style.display = "none";
    previewImg.src = "";

    if (!file) return;

    // Check file type
    if (!file.type.startsWith("image/")) {
        errorEl.textContent = "Only image files are allowed.";
        event.target.value = ""; // clear input
        return;
    }

    // Preview
    const reader = new FileReader();
    reader.onload = function(e) {
        previewImg.src = e.target.result;
        previewContainer.style.display = "block";
    };
    reader.readAsDataURL(file);
}
</script>


    <button type="submit" class="btn btn-primary">Create Module</button>
    <button type="button" class="btn btn-secondary" id="closesubmodel">Close</button>
</form>

<script>

    // Font Awesome icon preview
    document.getElementById('icon').addEventListener('input', function () {
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
$('#subform').on('submit', async function (e) {
    e.preventDefault();

    // Clear previous messages
    $('.error-text').text('');
    $('#general-errors').text('');
    $('#success-span').text('');

    const formData = new FormData(this);
    const token = $('input[name="_token"]').val() || $('meta[name="csrf-token"]').attr('content');
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-2"></i> Creating...');

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        const ct = response.headers.get('content-type') || '';
        let data = ct.includes('application/json') ? await response.json() : null;

        if (!response.ok) {
            if (response.status === 419) {
                $('#general-errors').text('Session expired or CSRF token mismatch.');
            } else if (data && data.errors) {
                Object.keys(data.errors).forEach(key => {
                    const normKey = key.replace(/\./g, '_');
                    $(`#${normKey}_error`).text(data.errors[key][0]);
                });
            } else {
                $('#general-errors').text(data?.message || `Server error: ${response.status}`);
            }
            return;
        }

        // ✅ Success
        $('#success-span').html(
            (data.message || 'Success!') +
            '<img src="/images/success/icons/check-mark.png" style="width:20px;margin-left:10px;" />'
        );

        this.reset();

        if (data?.redirect) {
            setTimeout(() => window.location.href = data.redirect, 1200);
        }

        setTimeout(() => {
            $('#success-span').text('');
        }, 3000);

        if (typeof window.loadModules === 'function') {
            window.loadModules();
        }

        $('#icon-preview').html('');
        $('#card-header-text').text('Create Module');
        $('#module_ids').val('');
    } catch (err) {
        console.error('Form submit error:', err);
        $('#general-errors').text('Something went wrong. Check console / network tab.');
    } finally {
        // Always re-enable the button
        submitBtn.prop('disabled', false).html(originalText);
    }
});

</script>