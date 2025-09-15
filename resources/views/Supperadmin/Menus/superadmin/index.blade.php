@extends('Supperadmin.layout.app')

@section('title', 'Supper Admin Menu')

@section('content')
<link rel="stylesheet" href="{{ asset('css/supperadmin/menu.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

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
    <script>
       $(document).on('click','#module', function(){
    $('#tab-eg1-0').addClass('active show');
$('#tab-eg1-1').removeClass('active show');
$('#tab-eg1-2').removeClass('active show');
$('#addmenubtn').text('');
$('#addmenubtn').html('<i class="fa fa-plus" aria-hidden="true"></i> Add Module');

$('a[href="#tab-eg1-0"]').addClass('active').attr('aria-selected', 'false');
$('a[href="#tab-eg1-1"]').removeClass('active').attr('aria-selected', 'true');
$('a[href="#tab-eg1-2"]').removeClass('active').attr('aria-selected', 'true');
       });


            $(document).on('click','#menu', function(){
    $('#tab-eg1-0').removeClass('active show');
$('#tab-eg1-1').addClass('active show');
$('#tab-eg1-2').removeClass('active show');
$('#addmenubtn').text('');
$('#addmenubtn').html('<i class="fa fa-plus" aria-hidden="true"></i> Add Menu');
$('a[href="#tab-eg1-0"]').removeClass('active').attr('aria-selected', 'false');
$('a[href="#tab-eg1-1"]').addClass('active').attr('aria-selected', 'true');
$('a[href="#tab-eg1-2"]').removeClass('active').attr('aria-selected', 'true');
       });
                $(document).on('click','#submenu', function(){
    $('#tab-eg1-0').removeClass('active show');
$('#tab-eg1-1').removeClass('active show');
$('#tab-eg1-2').addClass('active show');
$('#addmenubtn').text('');
$('#addmenubtn').html('<i class="fa fa-plus" aria-hidden="true"></i> Add SubMenu');

$('a[href="#tab-eg1-0"]').removeClass('active').attr('aria-selected', 'false');
$('a[href="#tab-eg1-1"]').removeClass('active').attr('aria-selected', 'true');
$('a[href="#tab-eg1-2"]').addClass('active').attr('aria-selected', 'true');
       });
    </script>

@endsection
