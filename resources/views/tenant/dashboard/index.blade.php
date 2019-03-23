@extends('tenant.layouts.app')

@push('styles')
    <style type="text/css">
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
    </style>
@endpush

@section('content')
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="tab-content">
            <div class="row">
                <div class="col-md-8">
                    <tenant-dashboard-grafic-box></tenant-dashboard-grafic-box>
                    <tenant-dashboard-alert-stock></tenant-dashboard-alert-stock>
                </div>
                <div class="col-md-4">
                    <tenant-dashboard-counts-bank></tenant-dashboard-counts-bank>                    
                </div>
            </div>
        </div>
    </div>
   

@endsection