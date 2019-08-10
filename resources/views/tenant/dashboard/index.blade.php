@extends('tenant.layouts.app')

@push('styles')
    {{-- <style type="text/css">
        .v-modal {
            opacity: 0.2 !important;
        }
        .border-custom {
            border-color: rgba(0,136,204, .5) !important;
        }
        @media only screen and (min-width: 768px) { 
        	.inner-wrapper {
			    padding-top: 60px !important;
			}
        }
        .card-header {
		    border-radius: 0px 0px 0px !important;
		}
    </style> --}}
@endpush

@section('content')
@can('tenant.dashboard.index')
    <tenant-dashboard-index></tenant-dashboard-index>
@elsecan('tenant.documents.store')
    <tenant-documents-invoice></tenant-documents-invoice> 
@endcan
@endsection