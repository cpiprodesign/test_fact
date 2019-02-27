@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatches-form2 :document_id="{{$document_id}}"></tenant-dispatches-form2>
@endsection
