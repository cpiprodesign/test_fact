@extends('tenant.layouts.app')

@section('content')
    @can('tenant.user.index')
        <tenant-users-index></tenant-users-index>
    @endcan

@endsection