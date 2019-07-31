@extends('tenant.layouts.app')

@section('content')
   @can('tenant.configuration.establishments.index')
    <tenant-establishments-index></tenant-establishments-index>
   @endcan
@endsection