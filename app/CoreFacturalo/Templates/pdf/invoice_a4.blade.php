@php 
    $establishment = $document->establishment;
    $customer = $document->customer;
    $invoice = $document->invoice;
    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);

    $establishment2 = \App\Models\Tenant\Establishment::find($document->establishment_id);
    $customer2 = \App\Models\Tenant\Person::find($document->customer_id);
    $configuration = \App\Models\Tenant\Configuration::first();
    $document_configuration = \App\Models\Tenant\DocumentConfiguration::first();
@endphp
<html>
    <head>
        <title>{{ $document_number }}</title>
        <link href="{{ $path_style }}" rel="stylesheet" />
        <style>
            html {
                font-family: sans-serif;
            }
        </style>
    </head>
    <body>
        <table class="full-width">
            <tr>
                @if($company->logo)
                    <td width="20%">
                        <div class="company_logo_box">
                            <img src="{{ asset('storage/uploads/logos/'.$company->logo) }}" alt="{{ $company->name }}" class="company_logo" style="max-width: 150px;">
                        </div>
                    </td>
                @else
                    <td width="20%">
                        <img src="{{ asset('logo/logo.jpg') }}" class="company_logo" style="max-width: 150px">
                    </td>
                @endif
                <td width="45%" class="pl-3">
                    <div class="text-left">
                        <h3 class="">{{ $company->name }}</h3>
                        <h5>{{ $establishment2->description }}</h5>
                        <h6>{{ strtoupper($establishment2->getAddressFullAttribute()) }}</h6>
                        <h6>{{ ($establishment2->telephone !== '-')? $establishment2->telephone : '' }}</h6>
                        <h6>{{ ($establishment2->email !== '-')? $establishment2->email : '' }}</h6>
                    </div>
                </td>
                <td width="35%" class="border-box py-4 px-1 text-center">
                    <h3 class="text-center">{{ 'R.U.C. N° '.$company->number }}</h3>
                    <h4 class="text-center">{{ $document->document_type->description }}</h4>
                    <h3 class="text-center"><strong>N° {{ $document_number }}</strong></h3>
                </td>
            </tr>
        </table><br>
        <div class="border-no-bottom py-2 px-1 pb-0">
            <table class="full-width mt-3 ">
                <tr>
                    <td width="15%">Cliente:</td>
                    <td width="45%">{{ $customer->name }}</td>
                    <td width="25%">Fecha de emisión:</td>
                    <td width="15%">{{ $document->date_of_issue->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>{{ $customer->identity_document_type->description }}:</td>
                    <td>{{ $customer->number }}</td>
                    @if($invoice)
                        <td>Fecha de vencimiento:</td>
                        <td>{{ $invoice->date_of_due->format('d/m/Y') }}</td>
                    @endif
                </tr>
                @if ($customer->address !== '')
                    <tr>
                        <td class="align-top">Dirección:</td>
                        <td colspan="2">{{ $customer2->getAddressFullAttribute() }}</td>
                    </tr>
                @endif
                @if ($document_configuration->seller)
                    <tr>
                        <td class="align-top">Vendedor:</td>
                        <td colspan="2">{{ $document->user->name }}</td>
                    </tr>
                @endif
            </table>
            @if ($document->guides)
                <br/>
                <table>
                    @foreach($document->guides as $guide)
                        <tr>
                            @if(isset($guide->document_type_description))
                            <td>{{ $guide->document_type_description }}</td>
                            @else
                            <td>{{ $guide->document_type_id }}</td>
                            @endif
                            <td>:</td>
                            <td>{{ $guide->number }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
        <div class="border-no-top py-4 px-1">
            <table class="full-width mt-12 mb-10">
                <thead>
                    <tr class="bg-grey">
                        <th class="border-top text-left py-1">ITEM</th>
                        <th class="border-top text-left py-1">COD.</th>
                        <th class="border-top text-left py-2">DESCRIPCIÓN</th>
                        <th class="border-top text-center py-1">CANT.</th>
                        <th class="border-top text-center py-2">UNIDAD</th>
                        <th class="border-top text-right py-2">P.UNIT</th>
                        @if($document->total_plastic_bag_taxes > 0)
                            <th class="border-top text-right py-2">ICBPER</th>
                        @endif
                        <th class="border-top text-right py-2">DTO.</th>
                        <th class="border-top text-right py-2">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1 @endphp
                    @foreach($document->items as $row)
                        <tr>
                            <td class="text-center align-top">{{ $i }}</td>
                            <td class="text-center align-top">{{ $row->item->internal_id }}</td>
                            <td class="text-left">
                                {!! $row->item->description !!}
                                @if($row->attributes)
                                    @foreach($row->attributes as $attr)
                                        <br/>{!! $attr->description !!} : {{ $attr->value }}
                                    @endforeach
                                @endif
                                @if($row->discounts)
                                    @foreach($row->discounts as $dtos)
                                        <br/><small>{{ $dtos->factor * 100 }}% {{$dtos->description }}</small>
                                    @endforeach
                                @endif
                            </td>
                                @php
                                    $decimal = 0;
                                @endphp
                                @if (strlen(stristr($row->quantity, '.00')) == 0)
                                    @php
                                        $decimal = 2;
                                    @endphp
                                @endif
                            <td class="align-top">{{ number_format($row->quantity, $decimal) }}</td>
                            <td class="text-center align-top">{{ $row->item->unit_type_id }}</td>
                            <td class="text-right align-top">{{ number_format($row->unit_price, $configuration->decimal) }}</td>
                            @if($document->total_plastic_bag_taxes > 0)
                                <td class="text-right align-top">{{ $row->total_plastic_bag_taxes }}</td>
                            @endif
                            <td class="text-right align-top">
                                @if($row->discounts)
                                    @php
                                        $total_discount_line = 0;
                                        foreach ($row->discounts as $disto) {
                                            $total_discount_line = $total_discount_line + $disto->amount;
                                        }
                                    @endphp
                                    {{ number_format($total_discount_line, 2) }}
                                @else
                                0
                                @endif
                            </td>
                            <td class="text-right align-top">{{ number_format($row->total, 2) }}</td>
                        </tr>
                        @php $i++ @endphp
                    @endforeach
                </tbody>
            </table>
            <table class="full-width mt-3">
                @if ($document->purchase_order)
                    <tr>
                        <td width="25%"><strong>Orden de Compra:</strong> </td>
                        <td class="text-left">{{ $document->purchase_order }}</td>
                    </tr>
                @endif                
            </table>
            <table>
                <tr>
                     @if(isset($document->additional_information))
                        <tr>
                            <td colspan="2"><b>Observaciones:</td>
                            <td colspan="5">{!! nl2br(e($document->additional_information[0])) !!}</td>
                        </tr>
                    @endif
                </tr>
            </table>
        </div>
        <br>
        <div class="py-2 px-1">
            <table class="full-width">
                <tr>
                    <td class="text-center" width="40%">
                        @foreach($document->legends as $row)
                            <p>Son: <span class="font-bold">{{ $row->value }} {{ $document->currency_type->description }}</span></p>
                        @endforeach
                    </td>
                    <td rowspan="2" width="60%" class="text-right">
                        <table>
                            <tbody>
                                @if($document->total_exportation > 0)
                                    <tr>
                                        <td colspan="5" class="text-right font-bold">OP. EXPORTACIÓN: {{ $document->currency_type->symbol }}</td>
                                        <td class="text-right font-bold">{{ number_format($document->total_exportation, 2) }}</td>
                                    </tr>
                                @endif
                                @if($document->total_free > 0)
                                    <tr>
                                        <td colspan="5" class="text-right font-bold">OP. GRATUITAS: {{ $document->currency_type->symbol }}</td>
                                        <td class="text-right font-bold">{{ number_format($document->total_free, 2) }}</td>
                                    </tr>
                                @endif
                                @if($document->total_unaffected > 0)
                                    <tr>
                                        <td colspan="5" class="text-right font-bold">OP. INAFECTAS: {{ $document->currency_type->symbol }}</td>
                                        <td class="text-right font-bold">{{ number_format($document->total_unaffected, 2) }}</td>
                                    </tr>
                                @endif
                                @if($document->total_exonerated > 0)
                                    <tr>
                                        <td colspan="5" class="text-right font-bold">OP. EXONERADAS: {{ $document->currency_type->symbol }}</td>
                                        <td class="text-right font-bold">{{ number_format($document->total_exonerated, 2) }}</td>
                                    </tr>
                                @endif
                                @if($document->total_taxed > 0)
                                    <tr>
                                        <td colspan="5" class="text-right font-bold">OP. GRAVADAS: {{ $document->currency_type->symbol }}</td>
                                        <td class="text-right font-bold">{{ number_format($document->total_taxed, 2) }}</td>
                                    </tr>
                                @endif
                                @if($document->total_discount > 0)
                                    <tr>
                                        <td colspan="5" class="text-right font-bold">DESCUENTO TOTAL: {{ $document->currency_type->symbol }}</td>
                                        <td class="text-right font-bold">{{ number_format($document->total_discount, 2) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="5" class="text-right font-bold">IGV: {{ $document->currency_type->symbol }}</td>
                                    <td class="text-right font-bold">{{ number_format($document->total_igv, 2) }}</td>
                                </tr>
                                @if($document->total_plastic_bag_taxes > 0)
                                    <tr>
                                        <td colspan="5" class="text-right font-bold">ICBPER: {{ $document->currency_type->symbol }}</td>
                                        <td class="text-right font-bold">{{ number_format($document->total_plastic_bag_taxes, 2) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="5" class="text-right font-bold">TOTAL A PAGAR: {{ $document->currency_type->symbol }}</td>
                                    <td class="text-right font-bold">{{ number_format($document->total, 2) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" width="40%">
                        <div class="text-center"><img class="qr_code" src="data:image/png;base64, {{ $document->qr }}" /></div>
                        <br>
                        <p>Código Hash: {{ $document->hash }}</p>
                    </td>
                </tr>
            </table>            
        </div>
    </body>
</html>