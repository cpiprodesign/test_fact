@extends('tenant.layouts.app')

@section('content')
    @can('tenant.retentions.index')
    <tenant-retentions-index></tenant-retentions-index>
    @endcan

@endsection