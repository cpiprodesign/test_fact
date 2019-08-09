@extends('tenant.layouts.app')

@section('content')
    @can('tenant.payments.index')
    <tenant-payments-index></tenant-payments-index>
    @endcan

@endsection