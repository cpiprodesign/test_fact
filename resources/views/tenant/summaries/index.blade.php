@extends('tenant.layouts.app')

@section('content')
    @can('tenant.summaries.index')
    <tenant-summaries-index></tenant-summaries-index>
    @endcan

@endsection