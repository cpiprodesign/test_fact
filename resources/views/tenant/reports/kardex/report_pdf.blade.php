<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Kardex</title>
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
            
            .celda {
                text-align: center;
                padding: 5px;
                border: 0.1px solid black;
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
            
            p>strong {
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
            <p align="center" class="title"><strong>Reporte Kardex</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <tr>
                    <td>
                        <p><strong>Empresa: </strong>{{$company->name}}</p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong>{{date('Y-m-d')}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Ruc: </strong>{{$company->number}}</p>
                    </td>
                    <td>
                        <p><strong>Producto: </strong>{{$item->description}}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Establecimiento: </strong>{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</p>
                    </td>
                </tr>
            </table>
        </div>
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
                        @foreach($reports as $key => $value)
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
