@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta kardex</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('tenant.reports.kardex.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Establecimiento</label>
                                    <select name="selEstablishment" id="selEstablishment" class="form-control">
                                        @foreach ($establishments as $establishment)
                                            <option value="{{$establishment->id}}" @if ($establishment->id == $establishment_id) selected @endif>{{$establishment->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    {{Form::label('item_id', 'Producto')}}
                                    {{Form::select('item_id', $items->pluck('description', 'id'), old('item_id', request()->item_id), ['class' => 'form-control col-md-6'])}}
                                </div>                                
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(!empty($item_inicial))
                        <div class="box">
                            <div class="box-body no-padding">
                                <div style="margin-bottom: 10px">
                                    @can('tenant.reports.descargar')
                                    @if(isset($reports))
                                        <form action="{{route('tenant.report.kardex.pdf')}}" class="d-inline" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="item_id" value="{{old('item_id', request()->item_id)}}">
                                            <input type="hidden" value="{{$establishment_id}}" name="establishment_id">
                                            <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                            {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                        </form>
                                    <form action="{{route('tenant.report.kardex.report_excel')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" name="item_id" value="{{old('item_id', request()->item_id)}}">
                                        <input type="hidden" value="{{$establishment_id}}" name="establishment_id">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                        {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                    </form>
                                    @endif
                                    @endcan
                                </div>
                                <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                    <thead class="">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha y hora</th>
                                            <th>Tipo transacción</th>
                                            <th>Número</th>
                                            <th>Entrada</th>
                                            <th>Salida</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                    
                                        <tr>
                                            <td>1</td>
                                            <td>{{$item_inicial->created_at}}</td>
                                            <td>Entrada Inicial</td>
                                            <td>- - -</td>
                                            <td>{{number_format($item_inicial->stock_inicial, 2)}}</td>
                                            <td>0.00</td>
                                            <td>{{number_format($item_inicial->stock_inicial, 2)}}</td>
                                        </tr>                                                                    
                                        @php
                                            $balance = $item_inicial->stock_inicial;
                                            $i = 2;
                                        @endphp
                                        @foreach($reports as $key => $value)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$value->created_at}}</td>
                                            <td>{{$value->type2}}</td>
                                            <td>{{$value->series}}-{{$value->number}}</td>
                                            <td>{{($value->type == 'purchase') ? number_format($value->quantity, 2) : number_format(0, 2)}}</td>
                                            <td>{{($value->type == 'sale' || $value->type == 'sale-note') ? number_format($value->quantity, 2) : number_format(0, 2)}}</td>
                                            @php
                                                if ($value->type == 'purchase') $balance += $value->quantity;
                                                if ($value->type == 'sale' || $value->type == 'sale-note') $balance -= $value->quantity;
                                                $i++;
                                            @endphp
                                            <td>{{number_format($balance, 2)}}</td>
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
                    @endif          
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
