@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta de Documentos</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('tenant.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            <tenant-calendar2 :document_types="{{json_encode($documentTypes)}}" :customers="{{json_encode($customers)}}" :establishments="{{json_encode($establishments)}}"  data_d="{{$d ?? ''}}" data_a="{{$a ?? ''}}" td="{{$td ?? null}}" customer_td="{{$customer_td ?? null}}" establishment_td="{{$establishment_td ?? null}}"></tenant-calendar2>
                        </form>
                    </div>
                    @if(!empty($reports) && count($reports))
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                    <form action="{{route('tenant.report_pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$d}}" name="d">
                                        <input type="hidden" value="{{$a}}" name="a">
                                        <input type="hidden" value="{{$td}}" name="td">
                                        <input type="hidden" value="{{$customer_td}}" name="customer_td">
                                        <input type="hidden" value="{{$establishment_td}}" name="establishment_td">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                        {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                    </form>
                                <form action="{{route('tenant.report_excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$d}}" name="d">
                                    <input type="hidden" value="{{$td}}" name="td">
                                    <input type="hidden" value="{{$a}} " name="a">
                                    <input type="hidden" value="{{$customer_td}}" name="customer_td">
                                    <input type="hidden" value="{{$establishment_td}}" name="establishment_td">
                                    <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                    {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                </form>
                                @endif
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                        <thead class="">
                                            <tr>
                                                <th class="">#</th>
                                                <th class="">Establecimiento</th>
                                                <th class="">Tipo Documento</th>
                                                <th class="">Número</th>
                                                <th class="">Fecha emisión</th>
                                                <th class="">Cliente</th>
                                                <th class="">RUC</th>
                                                <th class="">Estado</th>
                                                <th class="">Estado de pago</th>
                                                <th class="">Total Gravado</th>
                                                <th class="">Total IGV</th>
                                                <th class="">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $total_taxed = 0;
                                                $total_igv = 0;
                                                $total = 0;
                                            @endphp
                                            @foreach($reports as $key => $value)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$value->establishment}}</td>
                                                    <td>{{$value->document_type}}</td>
                                                    <td>{{$value->series}}-{{$value->number}}</td>
                                                    <td>{{$value->date_of_issue}}</td>
                                                    <td>{{$value->name}}</td>
                                                    <td>{{$value->document_number}}</td>
                                                    <td>{{$value->status_type}}</td>
                                                    <td>
                                                        @if($value->status_paid == 1)
                                                            Pagado
                                                        @else
                                                            Pendiente
                                                        @endif
                                                    </td>
                                                    <td>{{$value->total_taxed}}</td>
                                                    <td>{{$value->total_igv}}</td>
                                                    <td>{{$value->total}}</td>
                                                </tr>
                                                @php
                                                    $i++;
                                                    $total_taxed = $value->total_taxed + $total_taxed;
                                                    $total_igv = $value->total_igv + $total_igv;
                                                    $total = $value->total + $total;
                                                @endphp                                            
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="8"></th>
                                                <th class="font-weight-bold">Totales</th>
                                                <th class="font-weight-bold">{{number_format($total_taxed, 2)}}</th>
                                                <th class="font-weight-bold">{{number_format($total_igv, 2)}}</th>
                                                <th class="font-weight-bold">{{number_format($total, 2)}}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="pagination-wrapper">
                                {{-- {{ $reports->appends(['search' => Session::get('form_document_list')])->render()  }} --}}
                                {{-- {{$reports->links()}} --}}
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
