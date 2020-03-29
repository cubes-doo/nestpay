<?php

return [
    'merchant' => [
        'clientId' => env('NESTPAY_MERCHANT_CLIENT_ID', ''),
        'storeKey' => env('NESTPAY_MERCHANT_STORE_KEY', ''),
        'storeType' => env('NESTPAY_MERCHANT_STORE_TYPE', '3D_PAY_HOSTING'),
        'okUrl' => env('NESTPAY_MERCHANT_OK_URL', ''),
        'failUrl' => env('NESTPAY_MERCHANT_FAIL_URL', ''),
        '3DGateUrl' => env('NESTPAY_MERCHANT_3D_GATE_URL', 'https://testsecurepay.eway2pay.com/fim/est3Dgate'),
        
        //API
        'apiName' => env('NESTPAY_MERCHANT_API_USERNAME', ''),
        'apiPassword' => env('NESTPAY_MERCHANT_API_PASSWORD', ''),
        'apiEndpointUrl' => env('NESTPAY_MERCHANT_API_ENDPOINT_URL', 'https://testsecurepay.eway2pay.com/fim/api'),
    ]
];