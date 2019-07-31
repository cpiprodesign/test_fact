@extends('tenant.layouts.app')

@section('content')
    @can('tenant.quotations.index')
    <tenant-quotations-index></tenant-quotations-index>
    @endcan

@endsection

@push('scripts')
<script type="text/javascript">
	$(function(){
    'use strict';
        $(".tableScrollTop,.tableWide-wrapper").scroll(function(){
            $(".tableWide-wrapper,.tableScrollTop")
                .scrollLeft($(this).scrollLeft());
        });
    });
</script>
@endpush