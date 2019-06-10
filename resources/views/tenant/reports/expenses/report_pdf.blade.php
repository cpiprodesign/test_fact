<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
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
            <p align="center" class="title"><strong>Reporte de Gastos</strong></p>
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
                    @if(!is_null($establishment))
                        <td>
                            <p><strong>Establecimiento: </strong>{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</p>
                        </td>
                    @endif
                </tr>
            </table>
        </div>
        @if(!empty($reports))
            <div class="">
                <div class="">
                    <table class="">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Establecimiento</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $total = 0;
                            @endphp
                            @foreach($reports as $key => $value)
                                <tr>
                                    <td class="celda">{{$i}}</td>
                                    <td class="celda">{{$value->establishment}}</td>
                                    <td class="celda">{{$value->user}}</td>
                                    <td class="celda">{{$value->date_of_issue}}</td>
                                    <td class="celda">{{$value->description}}</td>
                                    <td class="celda">{{$value->total}}</td>
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
                                <th class="font-weight-bold">Total</th>
                                <th class="font-weight-bold">{{number_format($total, 2)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @else
            <div class="callout callout-info">
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>