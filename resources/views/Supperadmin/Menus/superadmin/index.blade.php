@extends('Supperadmin.layout.app')

@section('title', 'Supper Admin Menu')

@section('content')
<link rel="stylesheet" href="{{ asset('css/supperadmin/menu.css') }}">

    @include('Supperadmin.Menus.superadmin.modelformcreate')
    <div class="btn-actions-pane-right">
        <div role="group" class="btn-group-sm nav btn-group">
            <a data-bs-toggle="tab" href="#tab-eg2-0" class="btn-shadow btn btn-primary active" id="module">Module</a>
            <a data-bs-toggle="tab" href="#tab-eg2-1" class="btn-shadow btn btn-primary" id="menu">Menu</a>
            <a data-bs-toggle="tab" href="#tab-eg2-2" class="btn-shadow btn btn-primary" id="submenu">Submenu</a>

        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-eg2-0" role="tabpanel">
            @include('Supperadmin.Menus.superadmin.table')
        </div>
        <div class="tab-pane" id="tab-eg2-1" role="tabpanel">
           @include('Supperadmin.Menus.superadmin.table2')
        </div>
        <div class="tab-pane" id="tab-eg2-2" role="tabpanel">
                @include('Supperadmin.Menus.superadmin.table3')
        </div>
    </div>

@endsection
