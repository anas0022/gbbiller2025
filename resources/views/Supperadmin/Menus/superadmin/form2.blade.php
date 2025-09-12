<form action="" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="module_name">Menu Name</label>
        <input type="text" id="module_name" name="module_name" class="form-control">
    </div>

    <div class="form-group">
        <label for="icon">Icon</label>
        <input type="text" id="icon2" name="icon" class="form-control" placeholder="e.g. fa fa-id-card">
        <p style="margin-top:10px;">
            Preview: <span id="icon-preview2"></span> <small id="icon-error2" style="color:red; display:none;">Invalid
                fa-icon class</small>
        </p>

    </div>
    <div class="form-group">
        <label for="link">Url</label>
        <input type="text" id="link" name="link" class="form-control" placeholder="e.g. /dashboard">
    </div>
    <div class="form-group">
        <label for="position">Module</label>
        <select name="module" id="" class="form-control">
            <option value="1">Select Module</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Create Module</button>
    <button type="button" class="btn btn-secondary" id="closemodal2">close</button>
</form>

<script>
    document.getElementById('icon2').addEventListener('input', function() {
        const preview = document.getElementById('icon-preview2');
        const errorMsg = document.getElementById('icon-error2');
        const className = this.value.trim();

        // Try to create the icon element
        preview.innerHTML = `<i class="${className}"></i>`;

        const iconEl = preview.querySelector("i");

        // Check if icon is rendered properly (Font Awesome hides invalid icons with ::before empty content)
        const style = window.getComputedStyle(iconEl, '::before');
        const hasIcon = style && style.content !== 'none' && style.content !== '';

        if (hasIcon) {
            errorMsg.style.display = "none"; // hide error
        } else {
            errorMsg.style.display = "inline"; // show error
        }
    });
</script>
