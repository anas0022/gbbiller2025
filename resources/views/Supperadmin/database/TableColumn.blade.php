<div class="btn-actions-pane-right" style="display:flex; justify-content:flex-end; margin-bottom:10px; width:100%;">
    <button class="btn-shine btn-wide btn-pill btn btn-warning btn-sm" data-bs-toggle="modal"
        data-bs-target="#maketablecolumn">
        <i class="fa fa-cog fa-spin mr-2"></i> Make Table Columns
    </button>
    <button class="btn-shine btn-wide btn-pill btn btn-info btn-sm ml-2" data-bs-toggle="modal"
        data-bs-target="#updateTableColumn">
        <i class="fa fa-plus mr-2"></i> Add Columns to Existing Table
    </button>
    <button class="btn-shine btn-wide btn-pill btn btn-danger btn-sm ml-2" data-bs-toggle="modal"
        data-bs-target="#deleteTableColumn">
        <i class="fa fa-trash mr-2"></i> Delete Columns from Table
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
                        let migrateBtn = !data ?
                            `<button class="btn btn-sm btn-warning migrate-btn" data-table="${row.table_name}">Migrate</button>` :
                            '';
                        let deleteBtn = data ?
                            `<button class="btn btn-sm btn-danger delete-table-btn ml-1" data-table="${row.table_name}">Delete</button>` :
                            '';
                        return icon + ' ' + migrateBtn + ' ' + deleteBtn;
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

        // Handle table deletion
        $(document).on('click', '.delete-table-btn', function() {
            let tableName = $(this).data('table');
            
            // Show confirmation dialog
            const confirmed = confirm(`Are you sure you want to delete the table "${tableName}"?\n\nThis will:\n- Drop the database table\n- Delete related migration files\n\nThis action cannot be undone!`);
            
            if (!confirmed) {
                return;
            }

            // Show loading state
            const deleteBtn = $(this);
            const originalHtml = deleteBtn.html();
            deleteBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '/delete-table',
                type: 'POST',
                data: {
                    table_name: tableName
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    alert(response.message || 'Table deleted successfully!');
                    $('.table-status-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while deleting the table';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert(errorMessage);
                },
                complete: function() {
                    // Reset button state
                    deleteBtn.prop('disabled', false).html(originalHtml);
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
                <div id="message-form"></div>
    <div class="form-group">
        <label for="nonMigratedModelSelect">Select Model</label>
        <select id="nonMigratedModelSelect" name="model" class="form-control" required>
            <option value="">--Select Model--</option>
        </select>
    </div>

  <div id="columnsContainer">
    <div class="form-row mb-2 columnRow border p-2 rounded">
        <!-- Column Name -->
        <div class="col">
            <input type="text" name="columns[0][name]" placeholder="Column Name" class="form-control" required>
        </div>

        <!-- Type -->
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

        <!-- Length (for string/char) -->
        <div class="col">
            <input type="number" name="columns[0][length]" placeholder="Length (e.g. 255)" class="form-control">
        </div>

        <!-- Nullable -->
        <div class="col">
            <select name="columns[0][nullable]" class="form-control">
                <option value="1">Nullable</option>
                <option value="0">Not Nullable</option>
            </select>
        </div>

        <!-- Default -->
        <div class="col">
            <input type="text" name="columns[0][default]" placeholder="Default" class="form-control">
        </div>

        <!-- Unique -->
        <div class="col">
            <select name="columns[0][unique]" class="form-control">
                <option value="0">Not Unique</option>
                <option value="1">Unique</option>
            </select>
        </div>

        <!-- Unsigned -->
        <div class="col">
            <select name="columns[0][unsigned]" class="form-control">
                <option value="0">Signed</option>
                <option value="1">Unsigned</option>
            </select>
        </div>

        <!-- Index -->
        <div class="col">
            <select name="columns[0][index]" class="form-control">
                <option value="0">No Index</option>
                <option value="1">Index</option>
            </select>
        </div>
    </div>
</div>


    <div class="d-flex justify-content-between mt-3">
        <button type="button" id="addColumnBtn" class="btn btn-secondary btn-sm">Add Another Column</button>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
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
                        $('#message-form').text(errorData.message || 'Something went wrong').addClass('alert alert-danger');
                      
                        return;
                    }

                    const data = await response.json();
                    
                       $('#message-form').text(data.message).addClass('alert alert-success');
                    
                        setInterval(() => {
                            $('#maketablecolumn').modal('hide');
                        }, 2000);

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

        <!-- Modal for adding columns to existing tables -->
        <div class="modal" tabindex="-1" role="dialog" id="updateTableColumn" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-80" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Columns to Existing Table</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateTableForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label for="migratedTableSelect">Select Table</label>
                                <select id="migratedTableSelect" name="table_name" class="form-control" required>
                                    <option value="">--Select Table--</option>
                                </select>
                            </div>

                            <div id="existingColumnsInfo" class="alert alert-info" style="display: none;">
                                <strong>Current columns:</strong> <span id="currentColumns"></span>
                            </div>

                            <div id="updateColumnsContainer">
                                <div class="form-row mb-2 updateColumnRow border p-2 rounded">
                                    <!-- Column Name -->
                                    <div class="col">
                                        <input type="text" name="columns[0][name]" placeholder="Column Name" class="form-control" required>
                                    </div>

                                    <!-- Type -->
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

                                    <!-- Length (for string/char) -->
                                    <div class="col">
                                        <input type="number" name="columns[0][length]" placeholder="Length (e.g. 255)" class="form-control">
                                    </div>

                                    <!-- Nullable -->
                                    <div class="col">
                                        <select name="columns[0][nullable]" class="form-control">
                                            <option value="1">Nullable</option>
                                            <option value="0">Not Nullable</option>
                                        </select>
                                    </div>

                                    <!-- Default -->
                                    <div class="col">
                                        <input type="text" name="columns[0][default]" placeholder="Default" class="form-control">
                                    </div>

                                    <!-- Unique -->
                                    <div class="col">
                                        <select name="columns[0][unique]" class="form-control">
                                            <option value="0">Not Unique</option>
                                            <option value="1">Unique</option>
                                        </select>
                                    </div>

                                    <!-- Unsigned -->
                                    <div class="col">
                                        <select name="columns[0][unsigned]" class="form-control">
                                            <option value="0">Signed</option>
                                            <option value="1">Unsigned</option>
                                        </select>
                                    </div>

                                    <!-- Index -->
                                    <div class="col">
                                        <select name="columns[0][index]" class="form-control">
                                            <option value="0">No Index</option>
                                            <option value="1">Index</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" id="addUpdateColumnBtn" class="btn btn-secondary btn-sm">Add Another Column</button>
                                <button type="submit" class="btn btn-primary btn-sm">Add Columns to Table</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for deleting columns from existing tables -->
        <div class="modal" tabindex="-1" role="dialog" id="deleteTableColumn" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Columns from Table</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="deleteTableForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label for="deleteTableSelect">Select Table</label>
                                <select id="deleteTableSelect" name="table_name" class="form-control" required>
                                    <option value="">--Select Table--</option>
                                </select>
                            </div>

                            <div id="deleteColumnsInfo" class="alert alert-warning" style="display: none;">
                                <strong>⚠️ Warning:</strong> This action will permanently delete the selected columns and all their data!
                            </div>

                            <div id="deleteColumnsContainer" style="display: none;">
                                <label class="form-label">Select Columns to Delete:</label>
                                <div id="columnsCheckboxes" class="border p-3 rounded" style="max-height: 300px; overflow-y: auto;">
                                    <!-- Columns will be loaded here -->
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger btn-sm" id="deleteColumnsBtn" disabled>Delete Selected Columns</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let updateColumnIndex = 1;

            // Load migrated tables
            async function loadMigratedTables() {
                try {
                    const response = await fetch('/migrated-tables');
                    const tables = await response.json();

                    const select = document.getElementById('migratedTableSelect');
                    tables.forEach(table => {
                        const option = document.createElement('option');
                        option.value = table.table_name;
                        option.textContent = `${table.name} (${table.table_name}) - ${table.columns_count} columns`;
                        option.dataset.columns = JSON.stringify(table.columns);
                        select.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading migrated tables:', error);
                }
            }

            // Show current columns when table is selected
            document.getElementById('migratedTableSelect').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const columns = JSON.parse(selectedOption.dataset.columns);
                    document.getElementById('currentColumns').textContent = columns.join(', ');
                    document.getElementById('existingColumnsInfo').style.display = 'block';
                } else {
                    document.getElementById('existingColumnsInfo').style.display = 'none';
                }
            });

            // Add new column row for updates
            document.getElementById('addUpdateColumnBtn').addEventListener('click', () => {
                const container = document.getElementById('updateColumnsContainer');
                const div = document.createElement('div');
                div.classList.add('form-row', 'mb-2', 'updateColumnRow', 'border', 'p-2', 'rounded');

                div.innerHTML = `
                    <div class="col">
                        <input type="text" name="columns[${updateColumnIndex}][name]" placeholder="Column Name" class="form-control" required>
                    </div>

                    <div class="col">
                        <select name="columns[${updateColumnIndex}][type]" class="form-control" required>
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
                        <input type="number" name="columns[${updateColumnIndex}][length]" placeholder="Length (e.g. 255)" class="form-control">
                    </div>

                    <div class="col">
                        <select name="columns[${updateColumnIndex}][nullable]" class="form-control">
                            <option value="1">Nullable</option>
                            <option value="0">Not Nullable</option>
                        </select>
                    </div>

                    <div class="col">
                        <input type="text" name="columns[${updateColumnIndex}][default]" placeholder="Default (optional)" class="form-control">
                    </div>

                    <div class="col">
                        <select name="columns[${updateColumnIndex}][unique]" class="form-control">
                            <option value="0">Not Unique</option>
                            <option value="1">Unique</option>
                        </select>
                    </div>

                    <div class="col">
                        <select name="columns[${updateColumnIndex}][unsigned]" class="form-control">
                            <option value="0">Signed</option>
                            <option value="1">Unsigned</option>
                        </select>
                    </div>

                    <div class="col">
                        <select name="columns[${updateColumnIndex}][index]" class="form-control">
                            <option value="0">No Index</option>
                            <option value="1">Index</option>
                        </select>
                    </div>

                    <div class="col">
                        <button type="button" class="btn btn-danger removeUpdateColumnBtn">X</button>
                    </div>
                `;

                container.appendChild(div);
                updateColumnIndex++;
            });

            // Remove update column row
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('removeUpdateColumnBtn')) {
                    e.target.closest('.updateColumnRow').remove();
                }
            });

            // Handle form submission for updating tables
            document.getElementById('updateTableForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                try {
                    const response = await fetch('/add-columns-to-table', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        showUpdateMessage(errorData.message || 'Something went wrong', 'danger');
                        return;
                    }

                    const data = await response.json();
                    showUpdateMessage(data.message, 'success');

                    // Reset form
                    this.reset();
                    document.getElementById('updateColumnsContainer').innerHTML = '';
                    document.getElementById('existingColumnsInfo').style.display = 'none';
                    updateColumnIndex = 1;

                    // Reload the main table
                    $('.table-status-table').DataTable().ajax.reload();

                } catch (error) {
                    console.error('Error:', error);
                    showUpdateMessage('Unexpected error occurred. Please try again.', 'danger');
                }
            });

            // Helper to show messages for update form
            function showUpdateMessage(message, type = 'success') {
                let container = document.getElementById('updateFormMessage');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'updateFormMessage';
                    document.getElementById('updateTableForm').prepend(container);
                }
                container.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }

            // Load migrated tables when page loads
            document.addEventListener('DOMContentLoaded', loadMigratedTables);

            // Delete columns functionality
            let deleteTableData = [];

            // Load tables for deletion (same as migrated tables)
            async function loadDeleteTables() {
                try {
                    const response = await fetch('/migrated-tables');
                    const tables = await response.json();
                    deleteTableData = tables;

                    const select = document.getElementById('deleteTableSelect');
                    select.innerHTML = '<option value="">--Select Table--</option>';
                    
                    tables.forEach(table => {
                        const option = document.createElement('option');
                        option.value = table.table_name;
                        option.textContent = `${table.name} (${table.table_name}) - ${table.columns_count} columns`;
                        select.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading tables for deletion:', error);
                }
            }

            // Show columns when table is selected for deletion
            document.getElementById('deleteTableSelect').addEventListener('change', function() {
                const selectedTable = this.value;
                const columnsContainer = document.getElementById('deleteColumnsContainer');
                const warningInfo = document.getElementById('deleteColumnsInfo');
                const deleteBtn = document.getElementById('deleteColumnsBtn');

                if (selectedTable) {
                    const tableData = deleteTableData.find(t => t.table_name === selectedTable);
                    if (tableData) {
                        // Show warning
                        warningInfo.style.display = 'block';
                        
                        // Show columns container
                        columnsContainer.style.display = 'block';
                        
                        // Load columns as checkboxes
                        const checkboxesContainer = document.getElementById('columnsCheckboxes');
                        checkboxesContainer.innerHTML = '';
                        
                        // Filter out system columns that shouldn't be deleted
                        const systemColumns = ['id', 'created_at', 'updated_at'];
                        const deletableColumns = tableData.columns.filter(col => !systemColumns.includes(col));
                        
                        if (deletableColumns.length === 0) {
                            checkboxesContainer.innerHTML = '<p class="text-muted">No deletable columns found (system columns are protected).</p>';
                            deleteBtn.disabled = true;
                        } else {
                            deletableColumns.forEach(column => {
                                const div = document.createElement('div');
                                div.className = 'form-check';
                                div.innerHTML = `
                                    <input class="form-check-input column-checkbox" type="checkbox" value="${column}" id="delete_${column}">
                                    <label class="form-check-label" for="delete_${column}">
                                        ${column}
                                    </label>
                                `;
                                checkboxesContainer.appendChild(div);
                            });
                            
                            // Enable delete button
                            deleteBtn.disabled = false;
                        }
                    }
                } else {
                    warningInfo.style.display = 'none';
                    columnsContainer.style.display = 'none';
                    deleteBtn.disabled = true;
                }
            });

            // Handle checkbox changes to enable/disable delete button
            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('column-checkbox')) {
                    const checkedBoxes = document.querySelectorAll('.column-checkbox:checked');
                    const deleteBtn = document.getElementById('deleteColumnsBtn');
                    deleteBtn.disabled = checkedBoxes.length === 0;
                }
            });

            // Handle delete form submission
            document.getElementById('deleteTableForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const checkedBoxes = document.querySelectorAll('.column-checkbox:checked');
                const columnsToDelete = Array.from(checkedBoxes).map(cb => cb.value);
                const tableName = document.getElementById('deleteTableSelect').value;

                if (columnsToDelete.length === 0) {
                    showDeleteMessage('Please select at least one column to delete.', 'warning');
                    return;
                }

                // Show confirmation dialog
                const confirmed = confirm(`Are you sure you want to delete these columns from '${tableName}'?\n\nColumns: ${columnsToDelete.join(', ')}\n\nThis action cannot be undone!`);
                
                if (!confirmed) {
                    return;
                }

                const formData = new FormData();
                formData.append('table_name', tableName);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                
                // Append each column as a separate array element
                columnsToDelete.forEach((column, index) => {
                    formData.append(`columns[${index}]`, column);
                });

                try {
                    const response = await fetch('/delete-columns-from-table', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        showDeleteMessage(errorData.message || 'Something went wrong', 'danger');
                        return;
                    }

                    const data = await response.json();
                    showDeleteMessage(data.message, 'success');

                    // Reset form
                    this.reset();
                    document.getElementById('deleteColumnsContainer').style.display = 'none';
                    document.getElementById('deleteColumnsInfo').style.display = 'none';
                    document.getElementById('deleteColumnsBtn').disabled = true;

                    // Reload the main table
                    $('.table-status-table').DataTable().ajax.reload();

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteTableColumn'));
                    modal.hide();

                } catch (error) {
                    console.error('Error:', error);
                    showDeleteMessage('Unexpected error occurred. Please try again.', 'danger');
                }
            });

            // Helper to show messages for delete form
            function showDeleteMessage(message, type = 'success') {
                let container = document.getElementById('deleteFormMessage');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'deleteFormMessage';
                    document.getElementById('deleteTableForm').prepend(container);
                }
                container.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }

            // Load delete tables when page loads
            document.addEventListener('DOMContentLoaded', loadDeleteTables);
        </script>
    @endpush
