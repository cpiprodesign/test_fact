@extends('tenant.layouts.app')

@section('content')
    @can('tenant.configuration.documents')
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <tenant-documents-configuration></tenant-documents-configuration>
        </div>
    </div>
    @endcan
@endsection
