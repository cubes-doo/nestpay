<?php

return [
    //The merchant config
    'merchant' => [
        //store
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
    ],

    //Throw exceptions or not 
    //If set to false the exception will still be available in \Cubes\Nestpay\Laravel\NestpayPaymentProcessedErrorEvent 
    'throwExceptions' => true,

    //The class of the eloquen model which must implement \Cubes\Netpay\Payment interface
    //If set ti null the PDO will be used on table nestpay_payments
    'paymentModel' => \Cubes\Nestpay\Laravel\PaymentModel::class,

    //nestpay::handle-unprocessed-payments command settings

    //the payment is not going to be considered as "unprocessed" if it is younger than -900 seconds
    //even if it has propertu "processed" equals 0.
    //this is because the customer need some time to enter creditcard number on hosted payment page
    //default is 900 seconds or 15 minutes
    'unprocessed_payments_not_before' => 900, //seconds 

    //for how long the unprocess payment is going to be kept in that state
    // after this time to live, the unprocessed payment is going to be marked as processed (processed = 1)
    //default is 5 days
    'unprocessed_payments_time_to_live' => 432000, //seconds

    //timeout between two Nestpay API calls in seconds
    'unprocessed_payments_api_call_timeout' => 2, //seconds
];