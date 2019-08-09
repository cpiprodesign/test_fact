@extends('tenant.layouts.app')

@section('content')
    @canany(['tenant.suppliers.index', 'tenant.customers.index'])
    <tenant-persons-index :type="{{ json_encode($type) }}"></tenant-persons-index>
    @endcanany
@endsection