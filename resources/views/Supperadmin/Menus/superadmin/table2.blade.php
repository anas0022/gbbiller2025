<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4" style="margin-top: 10px;">
    <div class="row">
        <div class="col-sm-12">
            <table style="width: 100%;" id=""
                class="table table-hover table-striped table-bordered dataTable dtr-inline menu-table" role="grid">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Menu</th>
                        <th>Module</th>
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
        var table = $('.menu-table').DataTable({
            responsive: true,
            columns: [
                { data: 'Icon', },
                { data: 'Menu', orderable: false },
                { data: 'Module', },
                { data: 'Route' },
                { data: 'Status', orderable: false },
                { data: 'Action', orderable: false }
            ]
        });

        // --- loadModules globally so other scripts can call it ---
        window.loadMenu = function () {
            $.ajax({
                url: '/get-menu/superadmin',
                method: 'GET',
                success: function (response) {
    console.log(response);
    table.clear();

    response.forEach(function (menu) {
        var checked = menu.Status == 1 ? 'checked' : '';

        table.row.add({
            Icon: `<i class="${menu.module?.icon || ''} gradient-icon"></i>`,
            Menu: menu.Menuname,
            Module: menu.module?.modulename || '',
            Route: menu.route,
            Status: `
                <input type="checkbox" class="chkToggle2" 
                       data-id="${menu.id}"
                       data-toggle="toggle"
                       data-on="Active" data-off="Inactive"
                       data-onstyle="success" data-offstyle="danger" ${checked}>
            `,
            Action: `
                <button class="btn btn-sm btn-primary edit-menu"
                        data-id="${menu.id}"
                        data-name="${menu.Menuname}"
                        data-route="${menu.route}"
                        data-module_id="${menu.Module_id}">
                  Edit
                </button>
                <button class="btn btn-sm btn-danger delete-btn2" 
                        data-id="${menu.id}" 
                        data-menu="${menu.Menuname}">
                  Delete
                </button>
            `
        });
    });

    table.draw();

    if ($.fn.bootstrapToggle) $('.chkToggle2').bootstrapToggle();

                },
                error: function (xhr, status, error) {
                    console.error('loadModules AJAX error:', status, error, xhr.responseText);
                }
            });
        };

        // 🔹 Call it once on page load
        loadMenu();
    });
    $(document).on('click', '.edit-menu', function () {

        const $b = $(this);
        const id = $b.data('id');
        const name = $b.data('name');
        const route = $b.data('icon');
        const module_id = $b.data('module_id');
        $('#createmenu').modal('show');
        $('#card-header-text').text('Edit Menu');
        $('#tab-eg1-0').removeClass('active show');
        $('#tab-eg1-1').addClass('active show');
        $('#tab-eg1-2').removeClass('active show');
        $('a[href="#tab-eg1-0"]').removeClass('active').attr('aria-selected', 'false');
        $('a[href="#tab-eg1-1"]').addClass('active').attr('aria-selected', 'true');

        $('a[href="#tab-eg1-2"]').removeClass('active').attr('aria-selected', 'false');

        $('#tab-eg2-0').removeClass('active show');
        $('#tab-eg2-1').addClass('active show');
        $('#tab-eg2-2').addClass('active show');

        $('a[href="#tab-eg2-0"]').removeClass('active').attr('aria-selected', 'false');
        $('a[href="#tab-eg2-1"]').addClass('active').attr('aria-selected', 'true');
        $('a[href="#tab-eg2-2"]').removeClass('active').attr('aria-selected', 'false');
        $('#moduels-for-menu').val(module_id);
        $('#id').val(id);
        $('#Menuname').val(name);
        $('#route').val(route);

        $('#success-span').text('');
    });

    $(document).on('change', '.chkToggle2', function () {
        const moduleId = $(this).data('id');
        const newStatus = $(this).prop('checked') ? 1 : 0;

        $.post('/superadmin/menu/update-status', {
            id: moduleId,
            status: newStatus,
            _token: $('input[name="_token"]').val() || $('meta[name="csrf-token"]').attr('content')
        }, function (res) {
            console.log("Status updated:", res);
              loadModules();
            loadsubmenu();
                loadMenu();
                loadsideMenu();
                  gettingmenu();
        gettingmodule();
        }).fail(function (xhr, status, err) {
            console.error("Error updating status:", status, err, xhr.responseText);
        });
    });

    $(function () {
        $(document).on('click', '.delete-btn2', function () {
            const moduleId = $(this).data('id');
            const menu = $(this).data('menu');
            $('#deleteConfirmModal2').data('id', moduleId).modal('show');
            $('#deleting-menu').text(menu + '?');
        });

        // Assuming you have a confirm button inside your modal
        $('#deleteConfirmModal2').on('click', function () {
            const moduleId = $('#deleteConfirmModal2').data('id');
            deleteModule2(moduleId);
        });

        function deleteModule2(moduleId) {
            const token = $('meta[name="csrf-token"]').attr('content'); // safer to use meta tag

            $.ajax({
                url: '/superadmin/menu/delete-menu/' + moduleId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success: function (response) {

                    $('#deleteConfirmModal2').modal('hide');

                    // Refresh DataTable
                     loadModules();
            loadsubmenu();
                loadMenu();
                loadsideMenu();
                 loadrouteavail();
                    loadroutes();
                      gettingmenu();
        gettingmodule();
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




    <div class="modal" tabindex="-1" role="dialog" id="deleteConfirmModal2" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Menu <span id="deleting-menu" class="text-warning"><span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteConfirmModal2"
                        onclick="deleteModule2()">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endpush