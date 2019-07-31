@extends('tenant.layouts.app')

@section('content')
    @can('tenant.pos.index')
        <tenant-pos-index></tenant-pos-index>
    @endcan

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
