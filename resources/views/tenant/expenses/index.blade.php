@extends('tenant.layouts.app')

@section('content')
    @can('tenant.expenses.index')
    <tenant-expenses-index></tenant-expenses-index>
    @endcan

@endsection