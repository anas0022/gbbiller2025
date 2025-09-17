<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4" style="margin-top: 10px;">
    <div class="row">
        <div class="col-sm-12">
            <table style="width: 100%;" id=""
                class="table table-hover table-striped table-bordered dataTable dtr-inline module-table" role="grid">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Module Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
    </div>
</div>



<script>

    $(function () {
        // --- DataTable ---
        var table = $('.module-table').DataTable({
            responsive: true,
            columns: [
                { data: 'icon', orderable: false },
                { data: 'modulename' },
                { data: 'status', orderable: false },
                { data: 'action', orderable: false }
            ]
        });

        // --- loadModules globally so other scripts can call it ---
        window.loadModules = function () {
            $.ajax({
                url: '/superadmin/Create Menu/menu-table',
                method: 'GET',
                success: function (response) {
                    table.clear();

                    response.forEach(function (module) {
                        var checked = module.status == 1 ? 'checked' : '';

                        table.row.add({
                            icon: `<i class="${module.icon} gradient-icon"></i>`,
                            modulename: module.modulename,
                            status: `
                            <input type="checkbox" class="chkToggle" 
                                   data-id="${module.id}"
                                   data-toggle="toggle"
                                   data-on="Active" data-off="Inactive"
                                   data-onstyle="success" data-offstyle="danger" ${checked}>
                        `,
                            action: `
                            <button class="btn btn-sm btn-primary edit-btn"
                                    data-id="${module.id}"
                                    data-name="${module.modulename}"
                                    data-icon="${module.icon}">
                              Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${module.id}">
                              Delete
                            </button>
                        `
                        });
                    });

                    table.draw();

                    // Re-init toggle plugin for new rows
                    if ($.fn.bootstrapToggle) $('.chkToggle').bootstrapToggle();
                },
                error: function (xhr, status, error) {
                    console.error('loadModules AJAX error:', status, error, xhr.responseText);
                }
            });
        };

        // Initial load + interval refresh
        loadModules();
        setInterval(loadModules, 5000);

        // --- Form submit ---
        $('#moduleForm').on('submit', async function (e) {
            e.preventDefault();
            $('#modulename_error, #icon_error, #general-errors, #success-span').text('');

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
                        $('#general-errors').text('Session expired or CSRF token mismatch.');
                    } else if (data && data.errors) {
                        for (const key in data.errors) {
                            $(`#${key}_error`).text(data.errors[key][0]);
                        }
                    } else {
                        $('#general-errors').text(data?.message || `Server error: ${response.status}`);
                    }
                    return;
                }

                // Success
                $('#success-span').html((data.message || 'Success!') + '<img src="/images/success/icons/check-mark.png" style="width:20px;margin-left:10px;" />');
                this.reset();
                if (data?.redirect) setTimeout(() => window.location.href = data.redirect, 1200);
                setTimeout(() => {
                    $('#success-span').text('')
                }, 3000);

             loadModules();
            loadsubmenu();
                loadMenu();
                loadingthegetmodules();
                $('#icon-preview').html('');
                $('#card-header-text').text('Create Module');
                $('#module_ids').val('');
            } catch (err) {
                console.error('Form submit error:', err);
                $('#general-errors').text('Something went wrong. Check console / network tab.');
            }
        });

        // --- Toggle status ---
        $(document).on('change', '.chkToggle', function () {
            const moduleId = $(this).data('id');
            const newStatus = $(this).prop('checked') ? 1 : 0;

            $.post('/superadmin/Create Menu/update-status', {
                id: moduleId,
                status: newStatus,
                _token: $('input[name="_token"]').val() || $('meta[name="csrf-token"]').attr('content')
            }, function (res) {
                console.log("Status updated:", res);
                 loadsideMenu();
                 loadingthegetmodules();
            }).fail(function (xhr, status, err) {
                console.error("Error updating status:", status, err, xhr.responseText);
            });
        });

        // --- Edit button click ---
        $(document).on('click', '.edit-btn', function () {
            const $b = $(this);
            const id = $b.data('id');
            const name = $b.data('name');
            const icon = $b.data('icon');

            $('#createmenu').modal('show');
            $('#card-header-text').text('Edit Module');
            $('#module_ids').val(id);
            $('#module_name').val(name);
            $('#icon').val(icon);
            $('#icon-preview').html(`<i class="${icon}"></i>`);
            $('#success-span').text('');
            $('#tab-eg1-0').addClass('active show');
        $('#tab-eg1-1').removeClass('active show');
        $('#tab-eg1-2').removeClass('active show');
        $('a[href="#tab-eg1-0"]').addClass('active').attr('aria-selected', 'false');
        $('a[href="#tab-eg1-1"]').removeClass('active').attr('aria-selected', 'true');

        $('a[href="#tab-eg1-2"]').removeClass('active').attr('aria-selected', 'false');

        $('#tab-eg2-0').addClass('active show');
        $('#tab-eg2-1').removeClass('active show');
        $('#tab-eg2-2').removeClass('active show');

        $('a[href="#tab-eg2-0"]').addClass('active').attr('aria-selected', 'false');
        $('a[href="#tab-eg2-1"]').removeClass('active').attr('aria-selected', 'true');
        $('a[href="#tab-eg2-2"]').removeClass('active').attr('aria-selected', 'false');
        });

    });



    $(function () {
        $(document).on('click', '.delete-btn', function () {
            const moduleId = $(this).data('id');
            $('#deleteConfirmModal').data('id', moduleId).modal('show');
        });

        // Assuming you have a confirm button inside your modal
        $('#confirmDeleteBtn').on('click', function () {
            const moduleId = $('#deleteConfirmModal').data('id');
            deleteModule(moduleId);
        });

        function deleteModule(moduleId) {
            const token = $('meta[name="csrf-token"]').attr('content'); // safer to use meta tag

            $.ajax({
                url: '/superadmin/CreateMenu/delete-module/' + moduleId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success: function (response) {

                    $('#deleteConfirmModal').modal('hide');

                    // Refresh DataTable
                    loadModules();
            loadsubmenu();
                loadMenu();
                loadsideMenu();
                 loadrouteavail();
                    loadroutes();
                },
                error: function (xhr) {
                    let msg = 'Error deleting module';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    alert(msg);
                    console.error(xhr);
                }
            });
        }


    });


</script>

@push('modals')




    <div class="modal" tabindex="-1" role="dialog" id="deleteConfirmModal" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this module?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn"
                        onclick="deleteModule()">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endpush