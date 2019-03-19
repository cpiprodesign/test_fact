<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Punto de Venta de {{ $pos->user->name }} el {{ $pos->created_at->format('Y-m-d H:i') }}</title>
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
                {{$pos->establishment->description}} /
                @if ($pos->establishment->address!=='-')
                    {{$pos->establishment->address}} -
                @endif
                {{$pos->establishment->department->description}} -
                {{$pos->establishment->district->description}}
            </td>
        </tr>
        <tr>
            <td>
                <strong>Aperturado Por:</strong>
                {{ $pos->user->name }}
                <small> {{ $pos->user->email }}</small>
                <br>
                <strong>Fecha Apertura:</strong> {{ $pos->created_at->format('Y-m-d H:i') }}
                @if (!is_null($pos->deleted_at))
                    <br>
                    <strong>Fecha Cierre:</strong> {{ $pos->deleted_at->format('Y-m-d H:i') }}
                @endif

            </td>
            <td>
                <u><strong>Montos de Operacion:</strong></u>
                <br>
                <strong>Apertura:</strong> S/. {{ number_format($pos->open_amount,2) }} /

                <strong>Ingresos:</strong> S/. {{ number_format($pos->close_amount,2) }} /

                <strong>Saldo:</strong> S/. {{ number_format($pos->open_amount + $pos->close_amount,2) }}
            </td>
        </tr>
    </table>
</div>
{{--@php--}}
{{--    var_dump($pos->sales[0]->toArray());--}}
{{--@endphp--}}
@if(count($pos->sales))
    <div class="">
        <div class=" ">
            <table class="">
                <thead>
                <tr>

                    <th>Fecha y hora</th>
                    <th>Documento</th>
                    <th>Saldo</th>
                    <th>Pagado</th>
                    <th>Devolución</th>
                    <th>Items</th>
                    <th>Detalles de Pago</th>
{{--                    <th>Codigo</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($pos->sales as $key => $sale)
                    <tr>

                        <td class="celda">
                            {{ $sale->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="celda">
                            {{$sale->document->series}} -
                            {{$sale->document->number}}
                        </td>
                        <td class="celda">
                            {{ $sale->document->currency_type->symbol }}
                            {{ number_format($sale->total,2) }}
                        </td>
                        <td class="celda">
                            {{ $sale->document->currency_type->symbol }}
                            {{ number_format($sale->payed,2) }}
                        </td>
                        <td class="celda">
                            {{ $sale->document->currency_type->symbol }}
                            {{ number_format(abs($sale->delta),2) }}
                        </td>
                        <td class="celda">
                            @foreach($sale->document->items as $item)
                                <div>

                                    <strong>
                                        {{ $item->item->internal_id }} -
                                        {{ $item->item->description }}
                                    </strong>

                                    x {{ number_format($item->quantity,2) }}
                                </div>
                            @endforeach
                        </td>
                        <td class="celda">
                            @foreach($sale->details as $details)
                                <div>

                                    <strong>
                                        {{ $details->type }} :
                                    </strong>

                                    {{ number_format($details->amount,2) }}
                                    @if(!is_null($details->reference))
                                        <small>( Ref: {{$details->reference}} )</small>
                                    @endif
                                </div>
                            @endforeach
                        </td>
{{--                        <td class="celda">--}}
{{--                            <img src="data:image/png;base64,{{$sale->document->qr}} " class="qr">--}}
{{--                        </td>--}}
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
