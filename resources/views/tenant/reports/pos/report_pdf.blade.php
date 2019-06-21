<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Punto de Venta de {{ $pos['user']->name }} el {{ $pos['box']->created_at->format('Y-m-d H:i') }}</title>
    <style>
        html {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            border: 1px solid black;
        }

        table > tr > td,
        table > tbody > tr > td
        {
            vertical-align: top;
        }

        .celda {
            text-align: center;
            padding: 5px;
            border: 0.1px solid black;
            vertical-align: top;
        }

        .celda > .qr {
            width: 60px;
        }

        th {
            padding: 5px;
            text-align: center;
            border-color: #0088cc;
            border: 0.1px solid black;
        }

        .title {
            font-weight: bold;
            padding: 5px;
            font-size: 20px !important;
            text-decoration: underline;
        }

        p > strong {
            margin-left: 5px;
            font-size: 13px;
        }

        thead {
            font-weight: bold;
            background: #0088cc;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
<div>
    <p align="center" class="title"><strong>Reporte Punto de Venta</strong></p>
</div>
<div style="margin-top:20px; margin-bottom:20px;">
    <table>
        <tr>
            <td>
                <strong>Empresa: </strong>{{$company->name}}
            </td>
            <td>
                <strong>Fecha: </strong>{{date('Y-m-d')}}
            </td>
        </tr>

        <tr>
            <td>
                <strong>Ruc: </strong>{{$company->number}}
            </td>
            <td>
                <strong>Establecimiento: </strong>
                {{$pos['box']->establishment->description}} /
                @if ($pos['box']->establishment->address!=='-')
                    {{$pos['box']->establishment->address}} -
                @endif
                {{$pos['box']->establishment->department->description}} -
                {{$pos['box']->establishment->district->description}}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Aperturado Por:</strong>
                {{ $pos['user']->name }}
                <small> {{ $pos['user']->email }}</small>
                <br>
                <strong>Fecha Apertura:</strong> {{ $pos['box']->created_at->format('Y-m-d H:i') }}
                @if (!is_null($pos['box']->deleted_at))
                    <br>
                    <strong>Fecha Cierre:</strong> {{ $pos['box']->deleted_at->format('Y-m-d H:i') }}
                @endif
            </td>
            <td>
                <u><strong>Montos de Operacion:</strong></u>
                <br>
                <strong>Apertura:</strong> S/. {{ number_format($pos['box']->open_amount,2) }} /

                <strong>Ingresos:</strong> S/. {{ number_format($pos['box']->close_amount,2) }} /

                <strong>Saldo:</strong> S/. {{ number_format($pos['box']->open_amount + $pos['box']->close_amount,2) }}
            </td>
        </tr>
    </table>
</div>
{{--@php--}}
{{--    var_dump($pos->sales[0]->toArray());--}}
{{--@endphp--}}
@if(count($pos['box']->sales))
    <div class="">
        <div class="">
            <table class="">
                <thead>
                    <tr>
                        <th>Tipo de Operación</th>
                        <th>Total</th>
                        <th>Detalles de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pos['detail_box'] as $row)
                        <tr>
                            <td class="celda">
                                {{$row->operation_type}}
                            </td>
                            <td class="celda">
                                {{ $row->symbol }} {{ $row->total}}
                            </td>
                            <td class="celda">
                                @if($row->operation_type == 'Gasto')
                                    {{ $row->detail}}
                                @else
                                    Documento N° {{ $row->series}} - {{ $row->number}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="callout callout-info">
        <p>No se realizaron ventas.</p>
    </div>
@endif
</body>
</html>