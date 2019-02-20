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
                            <tenant-calendar :document_types="{{json_encode($documentTypes)}}" data_d="{{$d ?? ''}}" data_a="{{$a ?? ''}}" td="{{$td ?? null}}"></tenant-calendar>
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                    <form action="{{route('tenant.report_pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$d}}" name="d">
                                        <input type="hidden" value="{{$a}}" name="a">
                                        <input type="hidden" value="{{$td}}" name="td">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                        {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                    </form>
                                <form action="{{route('tenant.report_excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$d}}" name="d">
                                    <input type="hidden" value="{{$td}}" name="td">
                                    <input type="hidden" value="{{$a}} " name="a">
                                    <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                    {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                </form>
                                @endif
                            </div>
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th class="">#</th>
                                        <th class="">Tipo Documento</th>
                                        <th class="">Número</th>
                                        <th class="">Fecha emisión</th>
                                        <th class="">Cliente</th>
                                        <th class="">RUC</th>
                                        <th class="">Estado</th>
                                        <th class="">Total Gravado</th>
                                        <th class="">Total IGV</th>
                                        <th class="">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $value)
                                    <tr>
                                        <td>{{$value->number}}</td>
                                        <td>{{$value->document_type->id}}</td>
                                        <td>{{$value->series}}-{{$value->number}}</td>
                                        <td>{{$value->date_of_issue->format('Y-m-d')}}</td>
                                        <td>{{$value->person->name}}</td>
                                        <td>{{$value->person->number}}</td>
                                        <td>{{$value->state_type->description}}</td>
                                        <td>{{$value->total_taxed}}</td>
                                        <td>{{$value->total_igv}}</td>
                                        <td>{{$value->total}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
