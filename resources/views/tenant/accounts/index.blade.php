@extends('tenant.layouts.app')

@section('content')
    @can('tenant.accounts.index')
    <tenant-accounts-index></tenant-accounts-index>
    @endcan

@endsection