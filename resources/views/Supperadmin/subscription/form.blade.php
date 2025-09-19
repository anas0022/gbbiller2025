<form action="{{ route('superadmin.subscription.store') }}" method="POST" enctype="multipart/form-data" id="subform">
    @csrf
    <input type="hidden" name="id" value="" id="subid">
    <span id="success-span" style="color: green; margin-top:10px; display:block;"></span>

    <span id="general-errors" style="color: red; margin-bottom: 10px;"></span>
    <div class="form-group">
        <label for="sub">Subscription</label>
        <input type="text" id="Sub_type" name="Sub_type" class="form-control">
        <span class="text-danger error-text Sub_type_error" id="Sub_type_error"></span>
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