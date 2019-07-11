@extends('tenant.layouts.app')

@section('content')

<div class="card">
    <div class="card-header bg-info">
        <h3 class="my-0">Listado de Comprobantes Pendientes de Envio</h3>
    </div>
    <div class="card-body">
        <div class="col-md-12">
            <small>* Se visualiza la lista de los comprobantes que aún no han sido informados a SUNAT y que estan por vencer</small>
        </div>
        <br>
        <div class="tabs tabs-info">
            <ul class="nav nav-tabs">
                <li class="nav-item active">
                    <a class="nav-link" href="#bf" data-toggle="tab"><i class="fas fa-receipt"></i> Documentos</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#recent" data-toggle="tab"><i class="fas fa-donate"></i> Pagos</a>
                </li> --}}
            </ul>
            <div class="tab-content">
                <div id="bf" class="tab-pane active">
                    <tenant-alerts-documents-index></tenant-alerts-documents-index>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection