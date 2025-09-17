<!-- jQuery and DataTables CDN -->


<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4" style="margin-top: 10px;">
    <div class="row">
        <div class="col-sm-12">
            <table style="width: 100%;" id=""
                class="table table-hover table-striped table-bordered dataTable dtr-inline submenu-table" role="grid">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Module</th>
                        <th>Menu</th>
                        <th>Name</th>
                        <th>Route</th>
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
        var table = $('.submenu-table').DataTable({
            responsive: true,
            columns: [
                { data: 'Icon', },
                { data: 'Module', orderable: false },
                { data: 'Menu', },
                { data: 'Name' },
                { data: 'Route' },
                { data: 'Status', orderable: false },
                { data: 'Action', orderable: false }
            ]
        });

        // --- loadModules globally so other scripts can call it ---
        window.loadsubmenu = function () {
            $.ajax({
                url: '/get-submenu/superadmin',
                method: 'GET',
                success: function (response) {
                    table.clear();

                    response.forEach(function (module) {
                        var checked = module.status == 1 ? 'checked' : '';

                        table.row.add({
                            Icon: `<i class="${module.module.icon} gradient-icon"></i>`,
                            Menu: module.submenu.Menuname,
                            Module: module.module.modulename,
                            Name: module.menuname,
                            Route: module.sub_route,
                            Status: `
                            <input type="checkbox" class="chkToggle3" 
                                   data-id="${module.id}"
                                   data-toggle="toggle"
                                   data-on="Active" data-off="Inactive"
                                   data-onstyle="success" data-offstyle="danger" ${checked}>
                        `,
                            Action: `
                            <button class="btn btn-sm btn-primary edit-submenu"
                                    data-id="${module.id}"
                                    data-name="${module.menuname}"
                                    data-icon="${module.sub_route}"
                                    data-module_id="${module.module.id}"
                                    data-menu="${module.submenu.id}"
                                    >
                              Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn3" data-id="${module.id}" data-menu="${module.menuname}">
                              Delete
                            </button>
                        `
                        });
                    });

                    table.draw();

                    if ($.fn.bootstrapToggle) $('.chkToggle3').bootstrapToggle();
                },
                error: function (xhr, status, error) {
                    console.error('loadModules AJAX error:', status, error, xhr.responseText);
                }
            });
        };

        // 🔹 Call it once on page load
        loadsubmenu();
    });

    $(document).on('click', '.edit-submenu', function () {

        const $b = $(this);
        const id = $b.data('id');
        const name = $b.data('name');
        const route = $b.data('icon');
        const module_id = $b.data('module_id');
        const menu = $b.data('menu');
        $('#createmenu').modal('show');
        $('#card-header-text').text('');
        $('#card-header-text').text('Edit Sub Menu');
        $('#tab-eg1-0').removeClass('active show');
        $('#tab-eg1-1').removeClass('active show');
        $('#tab-eg1-2').addClass('active show');

        $('a[href="#tab-eg1-0"]').removeClass('active').attr('aria-selected', 'false');
        $('a[href="#tab-eg1-1"]').removeClass('active').attr('aria-selected', 'false');
        $('a[href="#tab-eg1-2"]').addClass('active').attr('aria-selected', 'true');
        $('#moduels-for-submenu').val(module_id);
        $('#id2').val(id);
        $('#menuname').val(name);
        $('#sub_route').val(route);
        $('#parentmenu').val(menu);
        $('#success-span').text('');
    });
    $(document).on('change', '.chkToggle3', function () {
        const moduleId = $(this).data('id');
        const newStatus = $(this).prop('checked') ? 1 : 0;

        $.post('/superadmin/submenu/update-status', {
            id: moduleId,
            status: newStatus,
            _token: $('input[name="_token"]').val() || $('meta[name="csrf-token"]').attr('content')
        }, function (res) {
            console.log("Status updated:", res);
            loadsideMenu();
        
        }).fail(function (xhr, status, err) {
            console.error("Error updating status:", status, err, xhr.responseText);
        });
    });
    $(function () {
        $(document).on('click', '.delete-btn3', function () {
            const moduleId = $(this).data('id');
            const menu = $(this).data('menu');
            $('#deleteConfirmModal3').data('id', moduleId).modal('show');
            $('#deleting-menu3').text(menu + '?');
        });

        // Assuming you have a confirm button inside your modal
        $('#deleteConfirmModal3').on('click', function () {
            const moduleId = $('#deleteConfirmModal3').data('id');
            deleteModule3(moduleId);
        });

        function deleteModule3(moduleId) {

            const token = $('meta[name="csrf-token"]').attr('content'); // safer to use meta tag

            $.ajax({
                url: '/superadmin/menu/delete-submenu/' + moduleId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success: function (response) {

                    $('#deleteConfirmModal3').modal('hide');

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




    <div class="modal" tabindex="-1" role="dialog" id="deleteConfirmModal3" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Menu <span id="deleting-menu3" class="text-warning"><span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteConfirmModal3"
                        onclick="deleteModule3()">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endpush