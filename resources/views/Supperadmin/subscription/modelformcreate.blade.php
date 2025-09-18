
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
            var myModal = new bootstrap.Modal(document.getElementById('createmenu'));
            myModal.show();
        });



        // when modal opens, set default text = Create Module
     
      
   

 
        
    </script>
@endpush