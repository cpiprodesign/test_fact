@extends('tenant.layouts.app')

@section('content')
    @can('tenant.purchases.index')
    <tenant-purchases-index></tenant-purchases-index>
    @endcan
@endsection