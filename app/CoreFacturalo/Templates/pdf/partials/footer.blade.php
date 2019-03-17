@php
    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
    $accounts = \App\Models\Tenant\BankAccount::all();    
@endphp
<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>
<table class="full-width">
    <tr>
        <td><h5><strong>Cuentas bancarias: <strong></h5></td>
    </tr>
    <tr>
        <td>
            @foreach ($accounts as $account)
                <h6>{{ $account->description}} - {{ $account->bank->description}} - N° {{$account->number}} </h6>
            @endforeach
        </td>
    </tr>
    <tr>
        <td class="text-center desc font-bold">Para consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
    </tr>
</table>
</body>