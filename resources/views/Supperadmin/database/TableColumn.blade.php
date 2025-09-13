<div class="btn-actions-pane-right" style="display:flex; justify-content:flex-end; margin-bottom:10px; width:100%;">
    <button class="btn-shine btn-wide btn-pill btn btn-warning btn-sm" data-bs-toggle="modal"
        data-bs-target="#maketablecolumn">
        <i class="fa fa-cog fa-spin mr-2"></i> Make Table Columns
    </button>
</div>


<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4" style="margin-top: 10px;">
    <div class="row">
        <div class="col-sm-12">
            <table style="width: 100%;" id=""
                class="table table-hover table-striped table-bordered dataTable dtr-inline table-status-table"
                role="grid">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Table Name</th>
                        <th>Column Count</th>
                        <th>Migration status</th>

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
    $(function() {
        var table = $('.table-status-table').DataTable({
            responsive: true,
            ajax: {
                url: "/tables-status",
                type: "GET",
                dataSrc: "",
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            },
            columns: [{
                    data: null,
                    title: "#",
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'table_name',
                    title: 'Table Name'
                },
                {
                    data: 'columns_count',
                    title: 'Column Count'
                },
                {
                    data: 'migrated',
                    title: 'Migration status',
                    render: function(data, type, row) {
                        let icon = data ? '<i class="fa fa-check text-success"></i>' :
                            '<i class="fa fa-times text-danger"></i>';
                        let btn = !data ?
                            `<button class="btn btn-sm btn-warning migrate-btn" data-table="${row.table_name}">Migrate</button>` :
                            '';
                        return icon + ' ' + btn;
                    }
                }
            ]

        });
        $(document).on('click', '.migrate-btn', function() {
            let tableName = $(this).data('table');

            $.ajax({
                url: '/migrate-table',
                type: 'POST',
                data: {
                    table: tableName
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    alert(res.message);
                    $('.table-status-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'Error occurred!');
                }
            });
        });

    });
</script>


@push('modals')
    <div class="modal" tabindex="-1" role="dialog" id="maketablecolumn" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-80" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Make Table Column</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addColumnsForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="migration" id="migrationField">

                    <div class="form-group">
                        <label for="nonMigratedModelSelect">Select Model</label>
                        <select id="nonMigratedModelSelect" name="model" class="form-control" required>
                            <option value="">--Select Model--</option>
                        </select>
                    </div>

                    <div id="columnsContainer">
                        <div class="form-row mb-2 columnRow border p-2 rounded">
                            <div class="col">
                                <input type="text" name="columns[0][name]" placeholder="Column Name" class="form-control" required>
                            </div>
                            <div class="col">
                                <select name="columns[0][type]" class="form-control" required>
                                    <option value="string">string</option>
                                    <option value="integer">integer</option>
                                    <option value="bigInteger">bigInteger</option>
                                    <option value="boolean">boolean</option>
                                    <option value="text">text</option>
                                    <option value="longText">longText</option>
                                    <option value="date">date</option>
                                    <option value="datetime">datetime</option>
                                    <option value="timestamp">timestamp</option>
                                    <option value="float">float</option>
                                    <option value="decimal">decimal</option>
                                </select>
                            </div>
                            <div class="col">
                                <input type="number" name="columns[0][length]" placeholder="Length (e.g. 255)" class="form-control">
                            </div>
                            <div class="col">
                                <select name="columns[0][nullable]" class="form-control">
                                    <option value="1">Nullable</option>
                                    <option value="0">Not Nullable</option>
                                </select>
                            </div>
                            <div class="col">
                                <input type="text" name="columns[0][default]" placeholder="Default" class="form-control">
                            </div>
                            <div class="col">
                                <select name="columns[0][unique]" class="form-control">
                                    <option value="0">Not Unique</option>
                                    <option value="1">Unique</option>
                                </select>
                            </div>
                        </div>
                    </div>

                   

                    <div class="modal-footer">
                         <button type="button" id="addColumnBtn" class="btn-shine btn-wide btn-pill btn btn-dark btn-sm w-40">Add Column</button>
                        <button type="submit" class="btn-shine btn-wide btn-pill btn btn-warning btn-sm w-40">
                            <i class="fa fa-cog fa-spin mr-2"></i> Add Model
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .modal-dialog.modal-80 { max-width: 80%; margin: 1.75rem auto; }
    </style>
</div>
        <style>
            /* Custom modal width */
            .modal-dialog.modal-80 {
                max-width: 80%;
                margin: 1.75rem auto;
                /* keep vertical centering */
            }
        </style>
        <script>
            async function loadNonMigratedModels() {
                try {
                    const response = await fetch('/get-non-migrated-models');
                    const models = await response.json();

                    const select = document.getElementById('nonMigratedModelSelect');
                    models.forEach(model => {
                        const option = document.createElement('option');
                        option.value = model.class;
                        option.textContent = model.name + " (" + model.expected_table + ")";
                        select.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading non-migrated models:', error);
                }
            }

            document.addEventListener('DOMContentLoaded', loadNonMigratedModels);
        </script>

        <script>
            let columnIndex = 1;

            document.getElementById('addColumnBtn').addEventListener('click', () => {
                const container = document.getElementById('columnsContainer');
                const div = document.createElement('div');
                div.classList.add('form-row', 'mb-2', 'columnRow');

                div.innerHTML = `
        <div class="col">
            <input type="text" name="columns[${columnIndex}][name]" placeholder="Column Name" class="form-control" required>
        </div>

        <div class="col">
            <select name="columns[${columnIndex}][type]" class="form-control" required>
                <option value="string">string</option>
                <option value="integer">integer</option>
                <option value="text">text</option>
                <option value="boolean">boolean</option>
                <option value="date">date</option>
                <option value="datetime">datetime</option>
                <option value="float">float</option>
                <option value="decimal">decimal</option>
            </select>
        </div>

        <div class="col">
            <input type="number" name="columns[${columnIndex}][length]" placeholder="Length (e.g. 255)" class="form-control">
        </div>

        <div class="col">
            <select name="columns[${columnIndex}][nullable]" class="form-control">
                <option value="1">Nullable</option>
                <option value="0">Not Nullable</option>
            </select>
        </div>

        <div class="col">
            <input type="text" name="columns[${columnIndex}][default]" placeholder="Default (optional)" class="form-control">
        </div>

        <div class="col">
            <select name="columns[${columnIndex}][unique]" class="form-control">
                <option value="0">Not Unique</option>
                <option value="1">Unique</option>
            </select>
        </div>

        <div class="col">
            <button type="button" class="btn btn-danger removeColumnBtn">X</button>
        </div>
    `;

                container.appendChild(div);
                columnIndex++;
            });

            // remove row
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('removeColumnBtn')) {
                    e.target.closest('.columnRow').remove();
                }
            });

            document.getElementById('addColumnsForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                try {
                    const response = await fetch('/add-columns', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });


                    if (!response.ok) {
                        const errorData = await response.json();
                        showMessage(errorData.message || 'Something went wrong', 'danger');
                        return;
                    }

                    const data = await response.json();
                    showMessage(data.message, 'success');

                    // optional: reset form
                    this.reset();
                    document.getElementById('columnsContainer').innerHTML = ''; // clear extra rows

                } catch (error) {
                    console.error('Error:', error);
                    showMessage('Unexpected error occurred. Please try again.', 'danger');
                }
            });

            // helper to show bootstrap alert
            function showMessage(message, type = 'success') {
                let container = document.getElementById('formMessage');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'formMessage';
                    document.getElementById('addColumnsForm').prepend(container);
                }
                container.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
            }
            document.getElementById('nonMigratedModelSelect').addEventListener('change', function() {
                let model = this.value; // e.g. App\Models\SupperAdmin\Menu\SuperAdminMenu

                // Extract only class name → "SuperAdminMenu"
                let parts = model.split('\\');
                let className = parts[parts.length - 1];

                // Convert PascalCase → snake_case plural
                let snake = className.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase();
                if (!snake.endsWith('s')) {
                    snake = snake + 's'; // simple pluralization
                }

                // Build migration name correctly
                const migrationName = `create_${snake}_table`;

                document.getElementById('migrationField').value = migrationName;
            });
        </script>
    @endpush
