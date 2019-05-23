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
            <h3 align="center" class="title"><strong>Reporte Documentos</strong></h3>
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
                                <th>Establecimiento</th>
                                <th>Tipo Doc</th>
                                <th>Número</th>
                                <th>Fecha emisión</th>
                                <th>Cliente</th>
                                <th>RUC</th>
                                <th>Estado</th>
                                <th>Estado de pago</th>
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
                            @foreach($records as $key => $value)
                                <tr>
                                    <td class="celda">{{$i}}</td>
                                    <td class="celda">{{$value->establishment}}</td>
                                    <td class="celda">{{$value->document_type}}</td>
                                    <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                    <td class="celda">{{$value->date_of_issue}}</td>
                                    <td class="celda">{{$value->name}}</td>
                                    <td class="celda">{{$value->document_number}}</td>
                                    <td class="celda">{{$value->status_type}}</td>
                                    <td class="celda">
                                        @if($value->status_paid == 1)
                                            Pagado
                                        @else
                                            Pendiente
                                        @endif
                                    </td>
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
                                <th colspan="8"></th>
                                <th class="celda">Totales</th>
                                <th class="celda">{{number_format($total_taxed, 2)}}</th>
                                <th class="celda">{{number_format($total_igv, 2)}}</th>
                                <th class="celda">{{number_format($total, 2)}}</th>
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
