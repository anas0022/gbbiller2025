<style>
    #route {
        opacity: 0.1;
    }
    #url-menu {
        opacity: 0.1;
    }
    #url-menu.active{
        opacity: 1;
    }
      #route.active{
        opacity: 1;
    }
    #route-button {
        position: absolute;
        opacity: 1;
        z-index: 4000;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: block;
    }
    #route-button.not-active{
        display: none;
    }
</style>

<!-- ✅ Corrected: changed class to id -->
<div id="no-module-fount" style=" display:none;">
    <div style="width: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 5px; ">
    <img src="{{ asset('images/not found/not-found.gif') }}" style="width:200px; height: 200px;"> 
    <p>No active modules available. Please add one.</p>
    <button class="btn btn-primary" id="menu-adding-module-active">Add Module</button>
    
</div>
</div>
<form action="{{ route('create.menu') }}" method="POST" enctype="multipart/form-data" id="menu-form" style="display:none;">
    @csrf
    <input type="hidden" name="id" id="id">
    <span id="success-spans" style="color: green; margin-top:10px; display:block;"></span>
    <span id="general-error" style="color: red; margin-bottom: 10px;"></span>

    <div class="form-group">
        <label for="Menuname">Menu Name</label>
        <input type="text" id="Menuname" name="Menuname" class="form-control">
        <span class="text-danger error-text Menuname_error" id="Menuname_error"></span>
    </div>

 

    <div class="form-group">
        <label for="Module_id">Module</label>
        <select name="Module_id" class="form-control" id="moduels-for-menu"></select>
        <span class="text-danger error-text Module_id_error" id="Module_id_error"></span>
    </div>
   <div class="form-group" style="position: relative;">
        <button id="route-button" class="btn btn-primary" type="button" >NO SUB <i class="fa fa-minus"></i></button>
        <label for="route" id="url-menu">Url</label>
        <select id="route" name="route" class="form-control" disabled>
            <option value="">-- Select Route --</option>
        </select>
        <span class="text-danger error-text routes_error" id="route_error"></span>
    </div>
    <button type="submit" class="btn btn-primary">Create Menu</button>
    <button type="button" class="btn btn-secondary" id="closemodal2">Close</button>
</form>

<script>
$(function () {
    window.loadingthegetmodules = function() {
        $.ajax({
            url: '/get-modules',
            method: 'GET',
            success: function (response) {
                let $select = $('#moduels-for-menu');
                $select.empty();
                $select.append(`<option value="">-- Select Module --</option>`);

                if (response.length > 0) {
                    response.forEach(function (module) {
                        $select.append(`<option value="${module.id}">${module.modulename}</option>`);
                    });
                    $('#no-module-fount').hide();
                    $('#menu-form').show();
                } else {
                    $('#menu-form').hide();
                    $('#no-module-fount').show();
                }
            },
            error: function (xhr) {
                console.error("Error fetching modules:", xhr.responseText);
                $('#moduels-for-menu').html(`<option value="">Error loading modules</option>`);
                $('#menu-form').hide();
                $('#no-module-fount').show();
            }
        });
    }
    loadingthegetmodules();
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    window.loadroutes = function() {
        fetch("{{ route('available.routes') }}")
            .then(response => response.json())
            .then(data => {
                let select = document.getElementById("route");
                select.innerHTML = '<option value="">-- Select Route --</option>'; 
                data.forEach(route => {
                    let option = document.createElement("option");
                    option.value = route.uri;
                    option.textContent = route.uri;
                    select.appendChild(option);
                });
            });
    }
    loadroutes();
});
</script>

<script>
$('#menu-adding-module-active').click(function () {
    $('#tab-eg1-0').addClass('active show');
    $('#tab-eg1-1').removeClass('active show');
    $('#tab-eg1-2').removeClass('active show');
    $('a[href="#tab-eg1-0"]').addClass('active').attr('aria-selected', 'true');
    $('a[href="#tab-eg1-1"]').removeClass('active').attr('aria-selected', 'false');
    $('a[href="#tab-eg1-2"]').removeClass('active').attr('aria-selected', 'false');

    $('#tab-eg2-0').addClass('active show');
    $('#tab-eg2-1').removeClass('active show');
    $('#tab-eg2-2').removeClass('active show');
    $('a[href="#tab-eg2-0"]').addClass('active').attr('aria-selected', 'true');
    $('a[href="#tab-eg2-1"]').removeClass('active').attr('aria-selected', 'false');
    $('a[href="#tab-eg2-2"]').removeClass('active').attr('aria-selected', 'false');
});
</script>
<script>
    $('#route-button').click(function () {
       
        $('#route').addClass('active');
        $('#url-menu').addClass('active');
        $('#route').removeAttr('disabled');
         $('#route-button ').addClass('not-active');
    })
</script>