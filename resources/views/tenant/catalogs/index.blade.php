@extends('tenant.layouts.app')

@section('content')
    @can('tenant.catalogs.index')
    <div class="row">
        <div class="col-lg-6 col-md-12 ui-sortable">
            @can('tenant.catalogs.cuenta-bancaria')
            <tenant-bank_accounts-index></tenant-bank_accounts-index>
            @endcan
            @can('tenant.catalogs.monedas')
            <tenant-currency-types-index></tenant-currency-types-index>
            @endcan
            @can('tenant.catalogs.atributos-sunat')
            <tenant-attribute_types-index></tenant-attribute_types-index>
            @endcan
            @can('tenant.catalogs.categoria-productos')
            <tenant-item-category-index></tenant-item-category-index>
            @endcan
        </div>
        <div class="col-lg-6 col-md-12 ui-sortable">
            @can('tenant.catalogs.bancos')
            <tenant-banks-index></tenant-banks-index>
            @endcan
            @can('tenant.catalogs.unidades')
            <tenant-unit_types-index></tenant-unit_types-index>
            @endcan
            @can('tenant.catalogs.marcas')
            <tenant-trademarks-index></tenant-trademarks-index>
            @endcan
        </div>
    </div>
    @endcan
@endsection
