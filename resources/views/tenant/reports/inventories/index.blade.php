@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta de inventarios</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('tenant.reports.inventories.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-3">
                                    <select name="selEstablishment" id="selEstablishment" class="form-control">
                                        @foreach ($establishments as $establishment)
                                            <option value="{{$establishment->id}}" @if ($establishment->id == $establishment_id) selected @endif>{{$establishment->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(!empty($reports) && count($reports) > 0)
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                <div class="row">
                                    @can('tenant.reports.descargar')
                                    @if(isset($reports))
                                        <div class="col-md-2">
                                            <form action="{{route('tenant.report.inventories.pdf')}}" class="d-inline" method="POST">
                                                {{csrf_field()}}
                                                <input type="hidden" value="{{$establishment_id}}" name="establishment_id">
                                                <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                                {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                            </form>
                                        </div>
                                        <div class="col-md-2">
                                            <form action="{{route('tenant.report.inventories.report_excel')}}" class="d-inline" method="POST">
                                                {{csrf_field()}}
                                                <input type="hidden" value="{{$establishment_id}}" name="establishment_id">
                                                <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                                {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                            </form>
                                        </div>
                                    @endif 
                                    @endcan                                   
                                </div>
                            </div>
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th>Código Interno</th>
                                        <th>Descripción</th>
                                        <th>Inventario actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $value)
                                    <tr>
                                        <td>{{$value->internal_id}}</td>
                                        <td>{{$value->description}}</td>
                                        <td>{{$value->stock}}</td>
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
