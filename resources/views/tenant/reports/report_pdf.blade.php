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
            <p align="center" class="title"><strong>Reporte Documentos</strong></p>
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
                                <th>Número</th>
                                <th>Fecha emisión</th>
                                <th>Cliente</th>
                                <th>RUC</th>
                                <th>Estado</th>
                                <th>Total Gravado</th>
                                <th>Total IGV</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $total_taxed = 0;
                                $total_igv = 0;
                                $total = 0;
                            @endphp
                            @foreach($reports as $key => $value)
                                <tr>
                                    <td class="celda">{{$i}}</td>
                                    <td class="celda">{{$value->establishment}}</td>
                                    <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                    <td class="celda">{{$value->date_of_issue}}</td>
                                    <td class="celda">{{$value->name}}</td>
                                    <td class="celda">{{$value->document_number}}</td>
                                    <td class="celda">{{$value->status_type}}</td>
                                    <td class="celda">{{$value->total_taxed}}</td>
                                    <td class="celda">{{$value->total_igv}}</td>
                                    <td class="celda">{{$value->total}}</td>
                                </tr>
                                @php
                                    $i++;
                                    $total_taxed = $value->total_taxed + $total_taxed;
                                    $total_igv = $value->total_igv + $total_igv;
                                    $total = $value->total + $total;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6"></th>
                                <th class="font-weight-bold">Totales</th>
                                <th class="font-weight-bold">{{number_format($total_taxed, 2)}}</th>
                                <th class="font-weight-bold">{{number_format($total_igv, 2)}}</th>
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
