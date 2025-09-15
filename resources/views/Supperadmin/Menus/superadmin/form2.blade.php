<form action="{{ route('create.menu') }}" method="POST" enctype="multipart/form-data" id="menu-form">
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
        <label for="route">Url</label>
        <input type="text" id="route" name="route" class="form-control" placeholder="e.g. /dashboard">
                 <span class="text-danger error-text routes_error" id="route_error"></span>
    </div>
    <div class="form-group">
        <label for="Module_id">Module</label>
       <select name="Module_id" class="form-control" id="moduels-for-menu">

      
        </select>
          <span class="text-danger error-text module_id_error" id="module_id_error"></span>
    </div>

    <button type="submit" class="btn btn-primary">Create Menu</button>
    <button type="button" class="btn btn-secondary" id="closemodal2">close</button>
</form>


<script>
 $(function() {

$.ajax({
    url: '/get-modules',
    method: 'GET',
    success: function(response){
        let $select = $('#moduels-for-menu');
        $select.empty(); // clear old options

        $select.append(`<option value="">-- Select Module --</option>`);

        if (response.length > 0) {
            response.forEach(function(module) {
                $select.append(`<option value="${module.id}"> ${module.modulename}</option>`);


            });
        } else {
            $select.append(`<option value="">No modules found</option>`);
        }
    },
    error: function(xhr) {
        console.error("Error fetching modules:", xhr.responseText);
        $('#moduels-for-menu').html(`<option value="">Error loading modules</option>`);
    }
});

    });



     $('#menu-form').on('submit', async function (e) {
        e.preventDefault();
        $('#modulename_error, #icon_error, #general-error, #success-spans').text('');

        const formData = new FormData(this);
        const token = $('input[name="_token"]').val() || $('meta[name="csrf-token"]').attr('content');

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            const ct = response.headers.get('content-type') || '';
            let data = ct.indexOf('application/json') !== -1 ? await response.json() : null;

            if (!response.ok) {
                if (response.status === 419) {
                    $('#general-error').text('Session expired or CSRF token mismatch.');
                } else if (data && data.errors) {
                    for (const key in data.errors) {
                        $(`#${key}_error`).text(data.errors[key][0]);
                    }
                } else {
                    $('#general-error').text(data?.message || `Server error: ${response.status}`);
                }
                return;
            }

            // Success
            $('#success-spans').html((data.message || 'Success!') + '<img src="/images/success/icons/check-mark.png" style="width:20px;margin-left:10px;" />');
            loadModules();
                setTimeout(() => {
                $('#success-spans').text('')
            }, 3000);
              $('#Menuname').val('');
           
             $('#route').val('');

              $('#moduels-for-menu').val('');
         
$('#tab-eg1-0').removeClass('active show');
$('#tab-eg1-1').addClass('active show');
$('#tab-eg1-2').removeClass('active show');
$('a[href="#tab-eg1-0"]').removeClass('active').attr('aria-selected', 'false');
$('a[href="#tab-eg1-1"]').addClass('active').attr('aria-selected', 'true');

$('a[href="#tab-eg1-2"]').removeClass('active').attr('aria-selected', 'false');

$('#tab-eg2-0').removeClass('active show');
$('#tab-eg2-1').addClass('active show');
$('#tab-eg2-2').removeClass('active show');

$('a[href="#tab-eg2-0"]').removeClass('active').attr('aria-selected', 'false');
$('a[href="#tab-eg2-1"]').addClass('active').attr('aria-selected', 'true');
$('a[href="#tab-eg2-2"]').removeClass('active').attr('aria-selected', 'false');
        } catch (err) {
            console.error('Form submit error:', err);
            $('#general-error').text('Something went wrong. Check console / network tab.');
        }
    });

</script>
