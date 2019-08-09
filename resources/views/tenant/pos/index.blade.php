@extends('tenant.layouts.app')

@section('content')
    @canany(['tenant.documents.pos','tenant.pos.index'])
        <tenant-pos-index></tenant-pos-index>
    @endcanany

@endsection

@push('scripts')
    <script type="text/javascript">
        $(function () {
            'use strict';
            $(".tableScrollTop,.tableWide-wrapper").scroll(function () {
                $(".tableWide-wrapper,.tableScrollTop")
                    .scrollLeft($(this).scrollLeft());
            });
        });
    </script>
@endpush
