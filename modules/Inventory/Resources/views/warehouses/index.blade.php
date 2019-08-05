@extends('tenant.layouts.app')

@section('content')
    @can('tenant.warehouses.index')
    <warehouses-index></warehouses-index>
    @endcan

@endsection