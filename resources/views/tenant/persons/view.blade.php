@extends('tenant.layouts.app')

@php
    $total = (is_null($totals->total)) ? 0:$totals->total;
    $total2 = (is_null($totals->total2)) ? 0:$totals->total2;
    $total_paid = (is_null($totals->total_paid)) ? 0:$totals->total_paid;
    $total_paid2 = (is_null($totals->total_paid2)) ? 0:$totals->total_paid2;
@endphp
@section('content')
    <div class="page-header pr-0">
        <h2><a href="/receipt"><i class="fas fa-tachometer-alt"></i></a></h2>
        <ol class="breadcrumbs">
            <li class="active"><span>Cliente: {{$person->name}}</span> </li>           
        </ol>
    </div>
    <div class="row">
        <div class="col-md-4">
            <section class="card mb-2">
                <div class="card-body bg-primary">
                    <div class="widget-summary widget-summary-md">
                        <div class="widget-summary-col widget-summary-col-icon">
                            <div class="summary-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total Ventas</h4>
                                <div class="info">
                                    <strong class="amount">S/. {{ $total + $total2 }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <section class="card mb-2">
                <div class="card-body bg-success">
                    <div class="widget-summary widget-summary-md">
                        <div class="widget-summary-col widget-summary-col-icon">
                            <div class="summary-icon">
                                <i class="fas fa-donate"></i>
                            </div>
                        </div>
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total Pagado</h4>
                                <div class="info">
                                    <strong class="amount">S/. {{ $total_paid + $total_paid2}}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4">
            <section class="card mb-2">
                <div class="card-body bg-danger">
                    <div class="widget-summary widget-summary-md">
                        <div class="widget-summary-col widget-summary-col-icon">
                            <div class="summary-icon">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                        </div>
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total por Pagar</h4>
                                <div class="info">
                                    <strong class="amount">S/. {{ $total + $total2 - ($total_paid + $total_paid2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div> 
    <div class="row pt-3">
        <div class="col-md-12">
            <div class="tabs tabs-info">
                <ul class="nav nav-tabs">
                    <li class="nav-item active">
                        <a class="nav-link" href="#popular" data-toggle="tab"><i class="fas fa-shopping-cart"></i> Ventas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#recent" data-toggle="tab"><i class="fas fa-donate"></i> Pagos</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="popular" class="tab-pane active">
                        <div class="row col-md-12">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                    <thead class="">
                                        <tr>
                                            <th class="">#</th>
                                            <th class="">Tipo</th>
                                            <th class="">Número</th>
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
                                        @foreach($sells as $key => $value)
                                            @php
                                                $balance = $value->total - $value->total_paid
                                            @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{$value->type}}</td>
                                                <td>{{$value->series}} - {{$value->number}}</td>
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
                                            <th colspan="2"></th>
                                            <th class="font-weight-bold">Totales</th>
                                            <th class="font-weight-bold">{{number_format($total, 2)}}</th>
                                            <th class="font-weight-bold">{{number_format($total_paid, 2)}}</th>
                                            <th class="font-weight-bold">{{number_format($total_balance, 2)}}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="recent" class="tab-pane">
                        <div class="row col-md-12">
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo de Operación</th>
                                        <th>Fecha</th>
                                        <th>Cuenta</th>
                                        <th>Moneta</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $total = 0;
                                    @endphp
                                    @foreach($payments as $key => $value)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $value->operation_type }}</td>
                                            <td>{{ $value->date_of_issue }}</td>
                                            <td>{{ $value->series }} - {{ $value->number }}</td>
                                            <td>{{ $value->currency_type_id }}</td>
                                            <td>{{ $value->total }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                            $total = $value->total + $total;                                            
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4"></th>
                                        <th class="font-weight-bold">Totales</th>
                                        <th class="font-weight-bold">{{number_format($total, 2)}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush