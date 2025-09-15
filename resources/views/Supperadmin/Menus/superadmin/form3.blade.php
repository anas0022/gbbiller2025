<form action="/superadmin/submenu/create" method="POST" enctype="multipart/form-data" id="submenuform">
    @csrf
    <input type="hidden" name="id" id="id2">
    <span id="success-spans2" style="color: green; margin-top:10px; display:block;"></span>

    <span id="general-error2" style="color: red; margin-bottom: 10px;"></span>
    <div class="form-group">
        <label for="module_name">Menu Name</label>
        <input type="text" id="menuname" name="menuname" class="form-control">
        <span class="text-danger error-text menuname_error" id="menuname_error"></span>
    </div>


    <div class="form-group">
        <label for="link">Url</label>
        <input type="text" id="sub_route" name="sub_route" class="form-control" placeholder="e.g. /dashboard">
        <span class="text-danger error-text sub_route_error" id="sub_route_error"></span>
    </div>
    <div class="form-group">
        <label for="position">Module</label>
        <select name="menu_module" id="moduels-for-submenu" class="form-control">


        </select>
        <span class="text-danger error-text menu_module_error" id="menu_module_error"></span>

    </div>
    <div class="form-group">
        <label for="position">Parent Menu</label>
        <select name="menu_id" id="parentmenu" class="form-control">

        </select>
        <span class="text-danger error-text menu_id_error" id="menu_id_error"></span>
    </div>

    <button type="submit" class="btn btn-primary">Create Menu</button>
    <button type="button" class="btn btn-secondary" id="closemodal3">close</button>
</form>

<script>
    $(function () {
        window.gettingmodule = function () {
            $.ajax({
                url: '/get-modules',
                method: 'GET',
                success: function (response) {
                    let $select = $('#moduels-for-submenu');
                    $select.empty(); // clear old options

                    $select.append(`<option value="">-- Select Module --</option>`);

                    if (response.length > 0) {
                        response.forEach(function (module) {
                            $select.append(
                                `<option value="${module.id}"> ${module.modulename}</option>`
                            );


                        });
                    } else {
                        $select.append(`<option value="">No modules found</option>`);
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching modules:", xhr.responseText);
                    $('#moduels-for-submenu').html(`<option value="">Error loading modules</option>`);
                }
            });
        }
        window.gettingmenu = function () {
            $.ajax({
                url: '/get-menu',
                method: 'GET',
                success: function (response) {
                    let $select = $('#parentmenu');
                    $select.empty(); // clear old options

                    $select.append(`<option value="">-- Select Menu --</option>`);

                    if (response.length > 0) {
                        response.forEach(function (module) {
                            $select.append(
                                `<option value="${module.id}"> ${module.Menuname}</option>`);


                        });
                    } else {
                        $select.append(`<option value="">No modules found</option>`);
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching modules:", xhr.responseText);
                    $('#parentmenu').html(`<option value="">Error loading modules</option>`);
                }
            });
        }
        gettingmenu();
        gettingmodule();
    });




    $('#submenuform').on('submit', async function (e) {
        e.preventDefault();
        $('#modulename_error, #sub_route_error, #general-error2, #success-spans2').text('');

        const formData = new FormData(this);
        const token = $('input[name="_token"]').val() || $('meta[name="csrf-token"]').attr('content');

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
            let data = ct.indexOf('application/json') !== -1 ? await response.json() : null;

            if (!response.ok) {
                if (response.status === 419) {
                    $('#general-error2').text('Session expired or CSRF token mismatch.');
                } else if (data && data.errors) {
                    for (const key in data.errors) {
                        $(`#${key}_error`).text(data.errors[key][0]);
                    }
                } else {
                    $('#general-error2').text(data?.message || `Server error: ${response.status}`);
                    $('#menuname_error, #sub_route_error, #success-spans2, #menu_module_error,#menu_id_error').text('');
                    gettingmenu();
                    gettingmodule();
                    $('#menuname').val('');
                    $('#sub_route').val('');


                }
                return;
            }

            // Success
            $('#success-spans2').html((data.message || 'Success!') + '<img src="/images/success/icons/check-mark.png" style="width:20px;margin-left:10px;" />');
            /* loadModules(); */

            $('#menuname_error, #sub_route_error, #general-error2, #menu_module_error,#menu_id_error').text('');
            gettingmenu();
            gettingmodule();
            $('#menuname').val('');
            $('#sub_route').val('');
            loadModules();
            setTimeout(() => {
                $('#success-spans2').text('');
            }, 3000);

            $('#tab-eg1-0').removeClass('active show');
            $('#tab-eg1-1').removeClass('active show');
            $('#tab-eg1-2').addClass('active show');
            $('a[href="#tab-eg1-0"]').removeClass('active').attr('aria-selected', 'false');
            $('a[href="#tab-eg1-1"]').removeClass('active').attr('aria-selected', 'true');

            $('a[href="#tab-eg1-2"]').addClass('active').attr('aria-selected', 'false');

            $('#tab-eg2-0').removeClass('active show');
            $('#tab-eg2-1').removeClass('active show');
            $('#tab-eg2-2').addClass('active show');

            $('a[href="#tab-eg2-0"]').removeClass('active').attr('aria-selected', 'false');
            $('a[href="#tab-eg2-1"]').removeClass('active').attr('aria-selected', 'true');
            $('a[href="#tab-eg2-2"]').addClass('active').attr('aria-selected', 'false');

        } catch (err) {
            console.error('Form submit error:', err);
            $('#general-error2').text('Something went wrong. Check console / network tab.');
        }
    });


</script>