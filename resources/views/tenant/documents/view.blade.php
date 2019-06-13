@extends('tenant.layouts.app')

@section('content')
    <div class="page-header pr-0">
        <h2><a href="/receipt"><i class="fas fa-tachometer-alt"></i></a></h2>
        <ol class="breadcrumbs">
            <li class="active"><span>Detalle del comprobante</span> </li>
            <!-- <li><span class="text-muted">Facturas - Notas <small>(crédito y débito)</small> - Boletas - Anulaciones</small></span></li> -->
        </ol>
        <div class="right-wrapper pull-right">
            
        </div>
    </div>
    <div class="card mb-0 card-without-radius">
        <div class="card-title p-1">
            <h6 class="text-bold">{{$document->document_type->description}} {{$document->series}}-{{$document->number}}</h6>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <section class="card card-horizontal card-without-radius">
                        <div class="card-body text-center">
                            <p class="font-weight-semibold mb-0">Valor Total</p>
                            <h4 class="font-bold mt-0">S/. {{ $document->total }}</h4>
                        </div>
                    </section>
                </div>
                <div class="col-md-3">
                    <section class="card card-horizontal card-without-radius">
                        <div class="card-body text-center">
                            <p class="font-weight-semibold mb-0 mx-2">Cobrado</p>
                            <h4 class="font-bold mt-0 text-success">S/. {{ $document->total_paid }}</h4>
                        </div>
                    </section>
                </div>
                <div class="col-md-3">
                    <section class="card card-horizontal card-without-radius">
                        <div class="card-body text-center">
                            <p class="font-weight-semibold mb-0 mx-w">Por cobrar</p>
                            <h4 class="font-bold mt-0 text-danger">S/. {{ $document->total - $document->total_paid}}</h4>
                        </div>
                    </section>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12">
                    <iframe src="{{route('tenant.print.external', ['document', $document->external_id, 'a4'] )}}" frameborder="0" width="100%" height="550" marginheight="0" marginwidth="0" id="pdf"></iframe>
                </div>
            </div>
            <div class="row p-3">
                {{-- <div class="tab-content" id="myTabContent"> --}}
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Pagos recibidos</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Método de pago</th>
                                                <th>Cuenta</th>
                                                <th>Monto</th>
                                                <th>Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payments as $payment)
                                                <tr>
                                                    <th>{{$payment->date_of_issue}}</th>
                                                    <th>{{$payment->payment_method->description}}</th>
                                                    <th>{{$payment->account->name}}</th>
                                                    <th>{{$payment->total}}</th>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush