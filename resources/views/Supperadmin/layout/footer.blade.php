<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="app-wrapper-footer">
    <div class="app-footer">
        <div class="app-footer__inner">
            <div class="app-footer-left">
                <div class="footer-dots">
                  
              
               <div class="dropdown">
    <a class="dot-btn-wrapper" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
        <i class="dot-btn-icon lnr-earth icon-gradient bg-happy-itmeo"></i>
    </a>
    <div class="dropdown-menu p-3" style="width: 1000px; max-height: 160vh; overflow-y: auto; z-index: 400;">
        <!-- Dropdown Header -->
        <div class="dropdown-menu-header mb-3">
            <div class="dropdown-menu-header-inner pt-3 pb-3 bg-focus text-center text-white" style="position: relative;">
                <div class="menu-header-image opacity-25" 
                     style="background-image: url('assets/images/dropdown-header/city2.jpg'); position: absolute; top:0; left:0; width:100%; height:100%; z-index:0;"></div>
                <div class="menu-header-content position-relative" style="z-index:1;">
                    <h6 class="menu-header-subtitle mt-0">Choose Model to Add Columns</h6>
                </div>
            </div>
        </div>

        <!-- Form -->
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
                    <div class="dots-separator"></div>
                   <div class="dropdown">
    <a class="dot-btn-wrapper dd-chart-btn-2" href="/database" >
        <i class="icon text-danger icon-anim-pulse ion-ios-folder"></i>
        <div class="badge badge-dot badge-abs badge-dot-sm badge-warning">Notifications</div>
    </a>

    <!-- Dropdown menu -->
    <div class="dropdown-menu-xl rm-pointers dropdown-menu p-3">
        <!-- Header -->
        <div class="dropdown-menu-header mb-3">
            <div class="dropdown-menu-header-inner bg-premium-dark p-2 rounded">
                <div class="menu-header-image"
                     style="background-image: url('assets/images/dropdown-header/abstract4.jpg'); height: 60px; background-size: cover; border-radius: 5px;">
                </div>
                <div class="menu-header-content text-white mt-2">
                    <h5 class="menu-header-title mb-0">Create Model</h5>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form id="createModelForm" class="mb-3">
            <input type="text" name="model_name" class="form-control mb-1" placeholder="Enter model name with namespace" required>
            <small class="text-muted d-block mb-2">Example: SupperAdmin/Menu/SuperAdminModules</small>
            <div id="commandResult" class="text-success mb-2"></div>

            <button type="submit" class="btn-shine btn-wide btn-pill btn btn-warning btn-sm w-100">
                <i class="fa fa-cog fa-spin mr-2"></i> Add Model
            </button>
        </form>
    </div>
</div>

                </div>
            </div>
            <div class="app-footer-right">
                <ul class="header-megamenu nav">
                    <li class="nav-item">
                        <a data-placement="top" rel="popover-focus" data-offset="300" data-toggle="popover-custom"
                            class="nav-link" data-original-title="" title="">
                            Footer Menu
                            <i class="fa fa-angle-up ml-2 opacity-8"></i>
                        </a>
                        <div class="rm-max-width rm-pointers">
                            <div class="d-none popover-custom-content">
                                <div class="dropdown-mega-menu dropdown-mega-menu-sm">
                                    <div class="grid-menu grid-menu-2col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-6 col-xl-6">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">Overview</li>
                                                    <li class="nav-item">
                                                        <a class="nav-link">
                                                            <i class="nav-link-icon lnr-inbox"></i>
                                                            <span>Contacts</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link">
                                                            <i class="nav-link-icon lnr-book"></i>
                                                            <span>Incidents</span>
                                                            <div class="ml-auto badge badge-pill badge-danger">5</div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link">
                                                            <i class="nav-link-icon lnr-picture"></i>
                                                            <span>Companies</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a disabled="" class="nav-link disabled">
                                                            <i class="nav-link-icon lnr-file-empty"></i>
                                                            <span>Dashboards</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-sm-6 col-xl-6">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">Sales &amp; Marketing</li>
                                                    <li class="nav-item"><a class="nav-link">Queues</a></li>
                                                    <li class="nav-item"><a class="nav-link">Resource Groups</a></li>
                                                    <li class="nav-item">
                                                        <a class="nav-link">Goal Metrics
                                                            <div class="ml-auto badge badge-warning">3</div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item"><a class="nav-link">Campaigns</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-placement="top" rel="popover-focus" data-offset="300" data-toggle="popover-custom"
                            class="nav-link" data-original-title="" title="">
                            Grid Menu
                            <div class="badge badge-dark ml-0 ml-1">
                                <small>NEW</small>
                            </div>
                            <i class="fa fa-angle-up ml-2 opacity-8"></i>
                        </a>
                        <div class="rm-max-width rm-pointers">
                            <div class="d-none popover-custom-content">
                                <div class="dropdown-menu-header">
                                    <div class="dropdown-menu-header-inner bg-tempting-azure">
                                        <div class="menu-header-image opacity-1"
                                            style="background-image: url('assets/images/dropdown-header/city5.jpg');">
                                        </div>
                                        <div class="menu-header-content text-dark">
                                            <h5 class="menu-header-title">Two Column Grid</h5>
                                            <h6 class="menu-header-subtitle">Easy grid navigation inside popovers</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid-menu grid-menu-2col">
                                    <div class="no-gutters row">
                                        <div class="col-sm-6">
                                            <button
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                                <i
                                                    class="lnr-lighter text-dark opacity-7 btn-icon-wrapper mb-2"></i>Automation
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                <i
                                                    class="lnr-construction text-danger opacity-7 btn-icon-wrapper mb-2"></i>Reports
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                                <i
                                                    class="lnr-bus text-success opacity-7 btn-icon-wrapper mb-2"></i>Activity
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button
                                                class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-focus">
                                                <i
                                                    class="lnr-gift text-focus opacity-7 btn-icon-wrapper mb-2"></i>Settings
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <ul class="nav flex-column">
                                    <li class="nav-item-divider nav-item"></li>
                                    <li class="nav-item-btn clearfix nav-item">
                                        <div class="float-left">
                                            <button class="btn btn-link btn-sm">Link Button</button>
                                        </div>
                                        <div class="float-right">
                                            <button class="btn-shadow btn btn-info btn-sm">Info Button</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
<script>

document.getElementById('createModelForm').addEventListener('submit', function(e){
    e.preventDefault();
    alert('clicked');
    const model_name = this.model_name.value;
    const resultDiv = document.getElementById('commandResult');

   $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });
alert(model_name);
    $.ajax({
        url: '/make-model',
        method: 'POST',
        data: { model_name: model_name },
        success: function(response){
            resultDiv.textContent = response.message;
            resultDiv.style.color = 'green';
            document.getElementById('createModelForm').reset();
        },
        error: function(xhr){
            const errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred';
            resultDiv.textContent = errorMsg;
            resultDiv.style.color = 'red';
        }
    });
});

</script>

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

<script>let columnIndex = 1;

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
document.addEventListener('click', function (e) {
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