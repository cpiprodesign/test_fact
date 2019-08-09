@extends('tenant.layouts.app')

@section('content')
    @can('tenant.credit-notes.index')
    <tenant-credit-notes-index></tenant-credit-notes-index>
    @endcan
@endsection