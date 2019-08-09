@extends('tenant.layouts.app')

@section('content')
    @can('tenant.inventory.index')
    <inventory-index></inventory-index>
    @endcan
@endsection