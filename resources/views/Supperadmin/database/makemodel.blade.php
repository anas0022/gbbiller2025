<div class="btn-actions-pane-right" style="display:flex; justify-content:flex-end; margin-bottom:10px; width:100%;">
    <button class="btn-shine btn-wide btn-pill btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#makemodel">
        <i class="fa fa-cog fa-spin mr-2"></i> Make Model
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
        var table = $('.model-table').DataTable({
            responsive: true,
            ajax: {
                url: "/all-models",
                type: "GET",
                dataSrc: "",
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            },
            columns: [{
                    data: null,
                    title: "#",
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Index
                    }
                },
                {
                    data: null,
                    title: "Model Name",
                    render: function(data) {
                        return data.split('\\').pop(); // Just the class basename
                    }
                },
                {
                    data: null,
                    title: "Full Namespace",
                    render: function(data) {
                        return data; // Full namespace string
                    }
                }
            ]
        });
    });
</script>
@push('modals')
    <div class="modal" tabindex="-1" role="dialog" id="makemodel" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Model</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createModelForm" class="mb-3">
                        <input type="text" name="model_name" class="form-control mb-1"
                            placeholder="Enter model name with namespace" required>
                        <small class="text-muted d-block mb-2">Example: SupperAdmin/Menu/SuperAdminModules</small>
                        <div id="commandResult" class="text-success mb-2"></div>




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
    <script>
        $(document).on('submit', '#createModelForm', function(e) {
            e.preventDefault();

            const model_name = $(this).find('input[name="model_name"]').val();
            const resultDiv = $('#commandResult');

            $.ajax({
                url: '/make-model',
                type: 'POST',
                data: {
                    model_name: model_name
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    resultDiv.text(response.message).css('color', 'green');
                    $('#createModelForm')[0].reset();
                    $('#makemodel').modal('hide'); // close modal
                    $('.model-table').DataTable().ajax.reload(); // refresh table
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'An error occurred';
                    resultDiv.text(errorMsg).css('color', 'red');
                }
            });
        });
    </script>
@endpush
