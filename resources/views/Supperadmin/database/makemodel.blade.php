<div class="btn-actions-pane-right" style="display:flex; justify-content:flex-end; margin-bottom:10px; width:100%;">
    <button class="btn-shine btn-wide btn-pill btn btn-warning btn-sm" data-bs-toggle="modal"
        data-bs-target="#makemodel" onclick="console.log('Make Model button clicked')">
        <i class="fa fa-cog fa-spin mr-2"></i> Make Model
    </button>
    <button class="btn-shine btn-wide btn-pill btn btn-info btn-sm ml-2" onclick="testDeleteRoute()">
        <i class="fa fa-test mr-2"></i> Test Delete Route
    </button>
</div>



<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4" style="margin-top: 10px;">
    <div class="row">
        <div class="col-sm-12">
            <table style="width: 100%;" id=""
                class="table table-hover table-striped table-bordered dataTable dtr-inline model-table" role="grid">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Model Name</th>
                        <th>Full Namespace</th>
                        <th>Actions</th>
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
        console.log("jQuery loaded and document ready");

        // Test the route first
        $.ajax({
            url: "/test-models",
            type: "GET",
            success: function (data) {
                console.log("Test models response:", data);
            },
            error: function (xhr, status, error) {
                console.error("Test models error:", error);
            }
        });

        // Load data and populate table
        $.ajax({
            url: "/all-models",
            type: "GET",
            success: function (response) {
                console.log("Data received:", response.data);

                // Clear existing data
                $('.model-table tbody').empty();

                // Populate table manually
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function (model, index) {
                        var row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${model.name}</td>
                                <td>${model.namespace}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-model-btn" data-model="${model.class}">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        `;
                        $('.model-table tbody').append(row);
                    });
                } else {
                    $('.model-table tbody').append(
                        '<tr><td colspan="4" class="text-center">No models found</td></tr>');
                }

                // Initialize DataTable after populating
                var table = $('.model-table').DataTable({
                    responsive: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true
                });
            },
            error: function (xhr, status, error) {
                console.error("Error loading models:", error);
                $('.model-table tbody').append(
                    '<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>'
                );
            }
        });
    });

    // Test function for delete route
    function testDeleteRoute() {


        // Test with a known model class
        const testModelClass = 'App\\Models\\Auth\\UserModel';

        $.ajax({
            url: '/test-model-path/' + encodeURIComponent(testModelClass),
            type: 'GET',
            success: function (response) {
                console.log('Test model path response:', response);
                alert('Test completed. Check console for details.');
            },
            error: function (xhr) {
                console.error('Test model path error:', xhr);
                alert('Test failed. Check console for details.');
            }
        });
    }
</script>
@push('modals')
    <div class="modal" tabindex="-1" role="dialog" id="makemodel" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Model</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <span id="successMessage" class="text-success" style="display:none;"></span>
                <div class="modal-body">
                    <form id="createModelForm" class="mb-3">
                        <div id="formMessage"></div>
                        <div class="form-group mb-3">
                            <label for="model_name">Model Name</label>
                            <input type="text" name="model_name" id="model_name" class="form-control"
                                placeholder="Enter model name with namespace" required>
                            <small class="text-muted">Example: SupperAdmin/Menu/SuperAdminModules</small>
                            <div class="invalid-feedback" id="model_name_error"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn-shine btn-wide btn-pill btn btn-warning btn-sm w-100">
                                <i class="fa fa-cog fa-spin mr-2"></i> Add Model
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- delete confimation --}}

    <div class="modal" tabindex="-1" role="dialog" id="deleteConfirmModal" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this module <strong><span id="module_name"></span></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).on('submit', '#createModelForm', function (e) {
            console.log("Form submission triggered");
            e.preventDefault();

            // Clear previous validation messages
            clearValidationMessages();

            const model_name = $(this).find('input[name="model_name"]').val().trim();

            // Client-side validation
            if (!model_name) {
                showFieldError('model_name', 'Model name is required');
                return;
            }

            if (!/^[A-Za-z][A-Za-z0-9\/]*[A-Za-z0-9]$/.test(model_name)) {
                showFieldError('model_name', 'Model name must contain only letters, numbers, and forward slashes');
                return;
            }

            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-2"></i> Creating...');

            $.ajax({
                url: '/make-model',
                type: 'POST',
                data: {
                    model_name: model_name
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function (response) {
                    showMessage(response.message || 'Model created successfully!', 'success');
                    $('#createModelForm')[0].reset();

                    // Add new row directly into DataTable
                    let table = $('.model-table').DataTable();

                    table.row.add([
                        table.rows().count() + 1, // auto increment row number
                        response
                            .model_name, // model name (you should send this back from Laravel)
                        response.full_namespace, // full namespace (send this back too)
                        `<button class="btn btn-danger btn-sm delete-model-btn" data-id="${response.id}" data-model="${response.model_name}">
                                    <i class="fa fa-trash"></i> Delete
                             </button>`

                    ]).draw(false);

                    loadTablesStatus();
                    setTimeout(function () {
                        $('#makemodel').modal('hide');
                    }, 1500);
                },

                error: function (xhr) {
                    let errorMessage = 'An error occurred while creating the model';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            // Handle validation errors
                            Object.keys(xhr.responseJSON.errors).forEach(function (field) {
                                showFieldError(field, xhr.responseJSON.errors[field][0]);
                            });
                            return;
                        } else if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    }

                    showMessage(errorMessage, 'danger');
                },
                complete: function () {

                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        function showMessage(message, type = 'success') {
            const container = $('#formMessage');
            container.html(`
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <i class="fa fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);

            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
                setTimeout(function () {
                    container.find('.alert').fadeOut();
                }, 5000);
            }
        }

        function showFieldError(fieldName, message) {
            const field = $(`#${fieldName}`);
            const errorDiv = $(`#${fieldName}_error`);

            field.addClass('is-invalid');
            errorDiv.text(message).show();
        }

        function clearValidationMessages() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').hide();
            $('#formMessage').empty();
        }

        // Clear validation messages when modal is closed
        $('#makemodel').on('hidden.bs.modal', function () {
            console.log('Modal closed');
            clearValidationMessages();
            $('#createModelForm')[0].reset();
        });

        // Test modal opening
        $('#makemodel').on('shown.bs.modal', function () {
            console.log('Modal opened');
        });

        let deleteBtn = null; // store clicked delete button
        let modelClass = null;

        $(document).on('click', '.delete-model-btn', function () {
            deleteBtn = $(this);
            modelClass = deleteBtn.data('model');
            const modelName = modelClass.split('\\').pop();

            // Set the name inside modal
            $('#module_name').text(modelName);

            // Show modal
            $('#deleteConfirmModal').modal('show');
        });
        $('#confirmDeleteBtn').on('click', function () {
            if (!modelClass || !deleteBtn) return;

            const originalHtml = deleteBtn.html();
            deleteBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');

            $.ajax({
                url: '/delete-model',
                type: 'POST',
                data: {
                    model_class: modelClass
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function (response) {
                    $('.model-table').DataTable().row(deleteBtn.parents('tr')).remove().draw();

                    $('#successMessage').text(response.message || 'Model deleted successfully!').show()
                        .fadeOut(5000);

                    showMessage(response.message || 'Model deleted successfully!', 'success');
                },
                error: function (xhr) {
                    let errorMessage = 'An error occurred while deleting the model';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showMessage(errorMessage, 'danger');
                },
                complete: function () {
                    deleteBtn.prop('disabled', false).html(originalHtml);
                    $('#deleteConfirmModal').modal('hide'); // close modal
                    deleteBtn = null; // reset reference
                    modelClass = null;
                }
            });
        });
    </script>
@endpush