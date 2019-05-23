<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Kardex</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Kardex</strong></h3>
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
                    <td>
                        <p><strong>Producto: </strong></p>
                    </td>
                    <td align="center">{{$item->description}}</td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Establecimiento: </strong></p>
                    </td>
                    <td align="center">{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</td>
                </tr>
            </table>
        </div>
        <br>
        <div class="">
            <div class=" ">
                <table class="">
                    <thead>
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
                        @foreach($records as $key => $value)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$value->created_at}}</td>
                                <td>{{($value->type == 'sale') ? 'Venta' : 'Compra'}}</td>
                                <td>{{$value->series}}-{{$value->number}}</td>
                                <td>{{($value->type == 'purchase') ? number_format($value->quantity, 2) : number_format(0, 2)}}</td>
                                <td>{{($value->type == 'sale') ? number_format($value->quantity, 2) : number_format(0, 2)}}</td>
                                @php
                                    if ($value->type == 'purchase') $balance += $value->quantity;
                                    if ($value->type == 'sale') $balance -= $value->quantity;
                                    $i++;
                                @endphp
                                <td>{{number_format($balance, 2)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
