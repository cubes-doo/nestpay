<?php
require __DIR__ . '/../vendor/autoload.php';
header('Content-Type: text/plain');

$config = require __DIR__ . '/config.php';

$merchantService = new \Cubes\Nestpay\MerchantService($config);

$dao = require __DIR__ . '/dao.php';
$merchantService->setPaymentDao($dao);


$merchantService->onFailedPayment(function ($payment) {
	echo "FAILED!";
	print_r($payment);
})->onSuccessfulPayment(function($payment) {
	echo "SUCCESS!";
	print_r($payment);
})->onError(function($payment, $ex) {
	echo "ERROR!";
	print_r($payment);
	echo $ex->getMessage();
	echo $ex->getTraceAsString();
});

//second parameter "true" is for failed page
$payment = $merchantService->paymentProcess3DGateResponse($_POST, true);
print_r($payment);