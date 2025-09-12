<div class="header" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
    <p>Super Admin Menu</p>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createmenu">
        Add Menu <i class="fa fa-plus" aria-hidden="true"></i>
      </button>
</div>
<div class="modal" tabindex="-1" role="dialog" id="time-error-modal" style="display:block;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <video src="{{ asset('images/error/Times.mp4') }}" autoplay loop muted 
               style="width: 100px; height: 100px; display: block; margin: 0 auto;"></video>
        <p style="text-align:center;" id="time-out-msg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="close-modal">OK</button>
      </div>
    </div>
  </div>
</div>