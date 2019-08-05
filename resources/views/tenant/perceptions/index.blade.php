@extends('tenant.layouts.app')

@section('content')
    {{-- @can('tenant.perceptions.index') --}}
    <tenant-perceptions-index></tenant-perceptions-index>
    {{-- @endcan --}}
@endsection