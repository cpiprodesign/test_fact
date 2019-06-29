<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Ventas por Cliente</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                <tr>
                    <td>
                        <p><b>Empresa: </b></p>
                    </td>
                    <td align="center">
                        <p><strong>{{$company->name}}</strong></p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong></p>
                    </td>
                    <td align="center">
                        <p><strong>{{date('Y-m-d')}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Ruc: </strong></p>
                    </td>
                    <td align="center">{{$company->number}}</td>
                    @if(!is_null($establishment))
                        <td>
                            <p><strong>Establecimiento: </strong></p>
                        </td>
                        <td align="center">{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</td>
                    @endif
                </tr>
            </table>
        </div>
        <br>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Número</th>
                                <th>N° de Ventas</th>
                                <th>Total en ventas</th>
                                <th>Total pagado</th>
                                <th>Total por cobrar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $total_quantity = 0;
                                $total = 0;
                                $total_paid = 0;
                                $total_balance = 0;
                            @endphp
                            @foreach($records as $key => $value)
                                @php
                                    $balance = $value->total - $value->total_paid
                                @endphp
                                <tr>
                                    <td class="celda">{{$i}}</td>
                                    <td class="celda">{{$value->name}}</td>
                                    <td class="celda">{{$value->number}}</td>
                                    <td class="celda">{{$value->quantity}}</td>
                                    <td class="celda">{{$value->total}}</td>
                                    <td class="celda">{{$value->total_paid}}</td>
                                    <td class="celda">{{$balance}}</td>
                                </tr>
                                @php
                                    $i++;
                                    $total_quantity = $value->quantity + $total_quantity;
                                    $total = $value->total + $total;
                                    $total_paid = $value->total_paid + $total_paid;
                                    $total_balance = $balance + $total_balance;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2"></th>
                                <th class="celda">Totales</th>
                                <th class="celda">{{$total_quantity}}</th>
                                <th class="celda">{{number_format($total, 2)}}</th>
                                <th class="celda">{{number_format($total_paid, 2)}}</th>
                                <th class="celda">{{number_format($total_balance, 2)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @else
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
