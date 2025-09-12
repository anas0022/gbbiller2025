@extends('Supperadmin.layout.app')

@section('title', 'Supper Admin Menu')

@section('content')
@include('Supperadmin.Menus.superadmin.modelformcreate')
   @include('Supperadmin.Menus.superadmin.table')
@endsection
