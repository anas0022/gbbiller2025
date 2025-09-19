<div id="sub_table" class="dataTables_wrapper dt-bootstrap4" style="margin-top: 10px;">
    <div class="row">
        <div class="col-sm-12">
            <table style="width: 100%;" id=""
                class="table table-hover table-striped table-bordered dataTable dtr-inline sub-table" role="grid">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Sub_type</th>
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
        var table = $('.sub-table').DataTable({
            responsive: true,
            columns: [
                { data: 'icon', orderable: false },
                { data: 'Sub_type' },
                { data: 'status', orderable: false },
                { data: 'action', orderable: false }
            ]
        });
    
        // --- loadModules globally so other scripts can call it ---
        window.loadModules = function () {
            $.ajax({
                url: '/superadmin/all-subs',
                method: 'GET',
                success: function (response) {
                    table.clear();
    
                    response.forEach(function (module) {
                        var checked = module.status == 1 ? 'checked' : '';
    
                        table.row.add({
                            icon: `<i class="${module.icon} gradient-icon"></i>`,
                            Sub_type: module.Sub_type,
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
                                        data-name="${module.Sub_type}"
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
    
                    
                    if ($.fn.bootstrapToggle) $('.chkToggle').bootstrapToggle();
                },
                error: function (xhr, status, error) {
                    console.error('loadModules AJAX error:', status, error, xhr.responseText);
                }
            });
        };
    
        // Initial load + interval refresh
        loadModules();
      
    });
    $(document).on('click', '.edit-btn', function () {
        const $b = $(this);
        const id = $b.data('id');
        const name = $b.data('name');
        const icon = $b.data('icon');
    
        $('#createsub').modal('show');
    
        $('#subid').val(id);
        $('#Sub_type').val(name);
        $('#icon').val(icon);
        $('#icon-preview').html(`<i class="${icon}"></i>`);
        $('#success-span').text('');
    });
</script>