@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Reporte de Ventas</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('tenant.reports.sells.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            <tenant-calendar :document_types="{}" :establishments="{{json_encode($establishments)}}" data_d="{{$d ?? ''}}" data_a="{{$a ?? ''}}" establishment_td="{{$establishment_td ?? null}}"></tenant-calendar>
                        </form>
                    </div>
                    @if(!empty($records) && count($records))
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @can('tenant.reports.descargar')
                                @if(isset($records))
                                    <form action="{{route('tenant.report.sells.pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$d}}" name="d">
                                        <input type="hidden" value="{{$a}}" name="a">
                                        <input type="hidden" value="{{$establishment_td}}" name="establishment_td">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                    </form>
                                <form action="{{route('tenant.report.sells.excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$d}}" name="d">
                                    <input type="hidden" value="{{$a}} " name="a">
                                    <input type="hidden" value="{{$establishment_td}}" name="establishment_td">
                                    <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                </form>
                                @endif
                                @endcan
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                        <thead class="">
                                            <tr>
                                                <th class="">#</th>
                                                <th class="">Tipo</th>
                                                <th class="">Número</th>
                                                <th class="">Cliente</th>
                                                <th class="">N° Documento</th>
                                                <th class="">Total</th>
                                                <th class="">Total Pagado</th>
                                                <th class="">Pendiente</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $total = 0;
                                                $total_paid = 0;
                                                $total_balance = 0;
                                            @endphp
                                            @foreach($records as $key => $value)
                                                @php
                                                    $balance = $value->total - $value->total_paid
                                                @endphp
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$value->type}}</td>
                                                    <td>{{$value->series}} - {{$value->number}}</td>
                                                    <td>{{$value->name}}</td>
                                                    <td>{{$value->document_number}}</td>
                                                    <td>{{$value->total}}</td>
                                                    <td>{{$value->total_paid}}</td>
                                                    <td>{{$balance}}</td>
                                                </tr>
                                                @php
                                                    $i++;
                                                    $total = $value->total + $total;
                                                    $total_paid = $value->total_paid + $total_paid;
                                                    $total_balance = $balance + $total_balance;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4"></th>
                                                <th class="font-weight-bold">Totales</th>
                                                <th class="font-weight-bold">{{number_format($total, 2)}}</th>
                                                <th class="font-weight-bold">{{number_format($total_paid, 2)}}</th>
                                                <th class="font-weight-bold">{{number_format($total_balance, 2)}}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="pagination-wrapper">
                                {{-- {{ $records->appends(['search' => Session::get('form_document_list')])->render()  }} --}}
                                {{-- {{$records->links()}} --}}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="box box-body no-padding">
                        <strong>No se encontraron registros</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
