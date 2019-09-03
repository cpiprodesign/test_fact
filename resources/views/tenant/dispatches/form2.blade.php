@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatches-form2 :document="{{$document}}"></tenant-dispatches-form2>
@endsection
