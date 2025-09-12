<div class="card-header"
    style="display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
    <p>Super Admin Menu</p>
    <button type="button" class="btn btn-primary" id="addmenubtn">
        Add Menu <i class="fa fa-plus" aria-hidden="true"></i>
    </button>
</div>
@push('modals')
    <div class="modal" tabindex="-1" role="dialog" id="createmenu" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div>


                    <div class="main-card card">
                        <div class="card-header">

                            <i class="fa fa-box gradient-icon" style="margin-right: 5px; font-size:20px; "></i>
                            <span id="card-header-text">Create Module</span>
                            <div class="btn-actions-pane-right">
                                <div role="group" class="btn-group-sm nav btn-group" style="width:auto;">
                                    <a data-bs-toggle="tab" href="#tab-eg1-0"
                                        class="btn-shadow btn btn-primary active"> Module</a>
                                    <a data-bs-toggle="tab" href="#tab-eg1-1" class="btn-shadow btn btn-primary">
                                        Menu</a>
                                    <a data-bs-toggle="tab" href="#tab-eg1-2" class="btn-shadow btn btn-primary"> Sub
                                        Menu</a>

                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-eg1-0" role="tabpanel">
                                    @include('Supperadmin.Menus.superadmin.form1')
                                </div>
                                <div class="tab-pane" id="tab-eg1-1" role="tabpanel">
                                    @include('Supperadmin.Menus.superadmin.form2')
                                </div>
                                <div class="tab-pane" id="tab-eg1-2" role="tabpanel">
                                    @include('Supperadmin.Menus.superadmin.form3')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.getElementById('closemodal').addEventListener('click', function() {
            var myModalEl = document.getElementById('createmenu');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        });


        document.getElementById('closemodal2').addEventListener('click', function() {
            var myModalEl = document.getElementById('createmenu');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        });
        document.getElementById('closemodal3').addEventListener('click', function() {
            var myModalEl = document.getElementById('createmenu');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        });



        document.getElementById('addmenubtn').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('createmenu'));
            myModal.show();
        });


        // when modal opens, set default text = Create Module
        document.getElementById('createmenu').addEventListener('shown.bs.modal', function() {
            updateHeaderText('#tab-eg1-0'); // default first tab
        });

        // when tabs change, update header
        document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function(e) {
                let target = e.target.getAttribute("href");
                updateHeaderText(target);
            });
        });

        function updateHeaderText(tabId) {
            let headerText = '';
            if (tabId === '#tab-eg1-0') {
                headerText = 'Create Module';
            } else if (tabId === '#tab-eg1-1') {
                headerText = 'Create Menu';
            } else if (tabId === '#tab-eg1-2') {
                headerText = 'Create Sub Menu';
            }
            document.getElementById('card-header-text').innerText = headerText;
        }
    </script>
@endpush
