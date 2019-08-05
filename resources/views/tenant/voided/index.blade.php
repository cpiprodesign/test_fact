@extends('tenant.layouts.app')

@section('content')
    @can('tenant.voided.index')
    <tenant-voided-index></tenant-voided-index>
    @endcan
@endsection