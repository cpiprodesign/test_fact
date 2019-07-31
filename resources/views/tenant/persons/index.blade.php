@extends('tenant.layouts.app')

@section('content')
    @can('tenant.persons.suppliers.index', 'tenant.persons.suppliers.index')
    <tenant-persons-index :type="{{ json_encode($type) }}"></tenant-persons-index>
    @endcan
@endsection