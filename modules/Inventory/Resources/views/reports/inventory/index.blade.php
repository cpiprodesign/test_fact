@extends('tenant.layouts.app')
@php
    use App\Helpers\Functions;
@endphp
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
                        <form action="{{route('reports.inventory.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Almacén</label>
                                    <select name="selWarehouse" id="selWarehouse" class="form-control">
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{$warehouse->id}}" @if ($warehouse->id == $warehouse_id) selected @endif>{{$warehouse->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="el-form-item col-xs-12">
                                <div class="el-form-item__content">
                                    <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                    <form action="{{route('reports.inventory.pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$warehouse_id}}" name="warehouse_id">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                        {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                    </form>
                                <form action="{{route('reports.inventory.report_excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$warehouse_id}}" name="warehouse_id">
                                    <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                    {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                </form>
                                @endif
                            </div>
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Inventario actual</th>
                                        <th>Almacén</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $value)
                                    <tr>
                                        <td class="celda">{{$loop->iteration}}</td>
                                        <td class="celda">{{$value->item->description}}</td>
                                        <td class="celda">{{Functions::formaterDecimal($value->stock)}}</td>
                                        <td class="celda">{{$value->warehouse->description}}</td>
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
