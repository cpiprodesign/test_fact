@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Reporte de Ventas por Cliente</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('tenant.reports.customers.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            <tenant-calendar2 :document_types="{}" :establishments="{{json_encode($establishments)}}" :customers="{{json_encode($customers)}}" data_d="{{$d ?? ''}}" data_a="{{$a ?? ''}}" establishment_td="{{$establishment_td ?? null}}"></tenant-calendar2>
                        </form>
                    </div>
                    @if(!empty($records) && count($records))
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @can('tenant.reports.descargar')
                                @if(isset($records))
                                    <form action="{{route('tenant.report.customers.pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$d}}" name="d">
                                        <input type="hidden" value="{{$a}}" name="a">
                                        <input type="hidden" value="{{$customer_td}}" name="customer_td">
                                        <input type="hidden" value="{{$establishment_td}}" name="establishment_td">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                    </form>
                                <form action="{{route('tenant.report.customers.excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$d}}" name="d">
                                    <input type="hidden" value="{{$a}} " name="a">
                                    <input type="hidden" value="{{$customer_td}}" name="customer_td">
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
                                                <th class="">Cliente</th>
                                                <th class="">Número</th>
                                                <th class="">N° de Ventas</th>
                                                <th class="">Total en ventas</th>
                                                <th class="">Total pagado</th>
                                                <th class="">Total por cobrar</th>
                                                <th class="">Ver Informe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $total_quantity = 0;
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
                                                    <td>{{$value->name}}</td>
                                                    <td>{{$value->number}}</td>
                                                    <td>{{$value->quantity}}</td>
                                                    <td>{{$value->total}}</td>
                                                    <td>{{$value->total_paid}}</td>
                                                    <td>{{$balance}}</td>
                                                    <td class="text-center">
                                                        <a href="/persons/customers/view/{{$value->customer_id}}" class="btn btn-xs"><i class="fa fa-clipboard-list i-icon text-info"></i></a>
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                    $total_quantity = $value->quantity + $total_quantity;
                                                    $total = $value->total + $total;
                                                    $total_paid = $value->total_paid + $total_paid;
                                                    $total_balance = $balance + $total_balance;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2"></th>
                                                <th class="font-weight-bold">Totales</th>
                                                <th class="font-weight-bold">{{$total_quantity}}</th>
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
