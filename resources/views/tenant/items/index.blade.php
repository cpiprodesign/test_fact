@extends('tenant.layouts.app')

@section('content')
    @can('tenant.items.index')
        <tenant-items-index></tenant-items-index>
    @endcan
@endsection