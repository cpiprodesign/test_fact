@extends('tenant.layouts.app')

@section('content')
    @can('tenant.dispatches.index')
    <tenant-dispatches-index></tenant-dispatches-index>
    @endcan
@endsection
