@extends('tenant.pos.register_layout')

@section('content')



    <tenant-pos-register></tenant-pos-register>

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
