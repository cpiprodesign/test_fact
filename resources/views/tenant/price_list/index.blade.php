@extends('tenant.layouts.app')

@section('content')
    @can('tenant.price-list.index')
    <tenant-price-list-index></tenant-price-list-index>
    @endcan

@endsection