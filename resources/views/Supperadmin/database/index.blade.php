@extends('Supperadmin.layout.app')

@section('title', 'Database Table Creation')

@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading" style="width:50%; display:flex; align-items:center;">
                <div class="page-title-icon">
                    <i class="fa fa-archive icon-gradient bg-sunny-morning">
                    </i>
                </div>
                <div>Database Table Creation
                    <div class="page-title-subheading">Create Models and Tables with Custom Columns.
                    </div>
                </div>
            </div>
            <div class="btn-actions-pane-right page-title-heading"
                style="width:50%; gap:5px; display:flex; justify-content:flex-end;">
                <div role="group" class="btn-group-sm nav btn-group" style="width:50%; gap:5px; display:flex;">
                    <!-- Make Model -->
                    <a data-bs-toggle="tab" href="#tab-eg2-0" class="btn-shadow btn btn-primary active" id="module">
                        <i class="icon text-white icon-anim-pulse ion-cube"></i> Make Model
                    </a>

                    <!-- Make Table Columns -->
                    <a data-bs-toggle="tab" href="#tab-eg2-1" class="btn-shadow btn btn-primary" id="menu">
                        <i class="icon text-white icon-anim-pulse ion-grid"></i> Make Table Columns
                    </a>



                </div>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <div class="tab-pane active" id="tab-eg2-0" role="tabpanel">
            @include('Supperadmin.database.makemodel')
        </div>
        <div class="tab-pane" id="tab-eg2-1" role="tabpanel">
            @include('Supperadmin.database.TableColumn')
        </div>

    </div>


@endsection