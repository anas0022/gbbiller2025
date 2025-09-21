<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="app-wrapper-footer">
    <div class="app-footer">
        <div class="app-footer__inner">
            <div class="app-footer-left">
                <div class="footer-dots">


                    <div class="dropdown">
                        <a class="dot-btn-wrapper" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                         
                        </a>
                        <div class="dropdown-menu p-3"
                            style="width: 1000px; max-height: 160vh; overflow-y: auto; z-index: 400;">
                            <!-- Dropdown Header -->
                            <div class="dropdown-menu-header mb-3">
                                <div class="dropdown-menu-header-inner pt-3 pb-3 bg-focus text-center text-white"
                                    style="position: relative;">
                                    <div class="menu-header-image opacity-25"
                                        style="background-image: url('assets/images/dropdown-header/city2.jpg'); position: absolute; top:0; left:0; width:100%; height:100%; z-index:0;">
                                    </div>
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
                                            <input type="text" name="columns[0][name]" placeholder="Column Name"
                                                class="form-control" required>
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
                                            <input type="number" name="columns[0][length]"
                                                placeholder="Length (e.g. 255)" class="form-control">
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
                                            <input type="text" name="columns[0][default]" placeholder="Default"
                                                class="form-control">
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
                                    <button type="button" id="addColumnBtn" class="btn btn-secondary btn-sm">Add Another
                                        Column</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="dots-separator"></div>
                    <div class="dropdown">
                        <a class="dot-btn-wrapper dd-chart-btn-2" href="/database">
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
                                <input type="text" name="model_name" class="form-control mb-1"
                                    placeholder="Enter model name with namespace" required>
                                <small class="text-muted d-block mb-2">Example:
                                    SupperAdmin/Menu/SuperAdminModules</small>
                                <div id="commandResult" class="text-success mb-2"></div>

                                <button type="submit" class="btn-shine btn-wide btn-pill btn btn-warning btn-sm w-100">
                                    <i class="fa fa-cog fa-spin mr-2"></i> Add Model
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
<script>
    $.fn.dataTable.ext.errMode = 'none';
</script>