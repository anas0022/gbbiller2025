

<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4" style="margin-top: 10px;">
    <div class="row">
        <div class="col-sm-12">
            <table style="width: 100%;" id=""
                class="table table-hover table-striped table-bordered dataTable dtr-inline menu-table" role="grid">
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
$(document).ready(function(){

    // Initialize DataTable first
    var table = $('.menu-table').DataTable({
        responsive: true,
        columns: [
            { data: 'icon', orderable: false },
            { data: 'modulename' },
            { data: 'status', orderable: false },
            { data: 'action', orderable: false }
        ]
    });

    // Fetch modules via AJAX
    $.ajax({
        url: '/superadmin/Create Menu/menu-table',
        method: 'GET',
        success: function(response){
            console.log(response);

            // Clear existing table data
            table.clear();

            // Add new rows
            response.forEach(function(module){
                var checked = module.status == 1 ? 'checked' : '';

                table.row.add({
                    icon: `<i class="${module.icon} gradient-icon"></i>`,
                    modulename: module.modulename,
                    status: `
                        <input type="checkbox" class="chkToggle" data-toggle="toggle"
                               data-on="Active" data-off="Inactive"
                               data-onstyle="success" data-offstyle="danger" ${checked}>
                    `,
                    action: `
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${module.id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${module.id}">Delete</button>
                    `
                });
            });

            // Draw the updated table
            table.draw();

            // Re-init Bootstrap toggle for new rows
            $('.chkToggle').bootstrapToggle();
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });

});

</script>
