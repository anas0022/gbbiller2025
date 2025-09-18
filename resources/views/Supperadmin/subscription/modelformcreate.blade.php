
@push('modals')
    <div class="modal" tabindex="-1" role="dialog" id="createsub" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div>


                    <div class="main-card card">
                        

                        <div class="card-body">
                            @include('supperadmin.subscription.form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
      


        $(document).on('click', '#addmenubtn2', function () {
            var myModal = new bootstrap.Modal(document.getElementById('createsub'));
            myModal.show();
        });
           document.getElementById('closesubmodel').addEventListener('click', function () {
            var myModalEl = document.getElementById('createsub');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();
        });



    
 
        
    </script>
@endpush