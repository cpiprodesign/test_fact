<?php

return [
    'signature_note' => env('SIGNATURE_NOTE_OSE', 'FACTURALO'),
    'signature_uri' => env('SIGNATURE_URI_OSE', 'signatureFACTURALO'),
    'ose_demo' => 'https://demo-ose.nubefact.com/ol-ti-itcpe/billService?wsdl',
    'ose_production' => 'https://ose.nubefact.com/ol-ti-itcpe/billService?wsdl',
    'api_service_url' => env('API_SERVICE_URL'),
    'api_service_token' => env('API_SERVICE_TOKEN', false),
    'sunat_alternate_server' => env('SUNAT_ALTERNATE_SERVER', false),
];
