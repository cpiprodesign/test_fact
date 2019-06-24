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
                                    <strong class="amount">S/. {{ $total + $total2 - ($total_paid + $total_paid2)}}</strong>
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
                        <tenant-reports-customers-sells :id="{{ json_encode($person->id) }}"></tenant-reports-customers-sells>
                    </div>
                    <div id="recent" class="tab-pane">
                        <p>Recent</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush