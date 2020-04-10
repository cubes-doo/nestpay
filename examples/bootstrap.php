<?php
require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/config.php';

$merchantService = new \Cubes\Nestpay\MerchantService($config);

$merchantService->setPDO(
    new \PDO('mysql:host=db;dbname=nestpay', 'root', 'root', [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ])
);

$merchantService->onFailedPayment(function ($payment) {
	echo "FAILED PAYMENT!";
	print_r($payment);
})->onSuccessfulPayment(function($payment) {
	echo "SUCCESSFUL PAYMENT!";
	print_r($payment);
})->onError(function($payment, $ex) {
	echo "ERROR ON PAYMENT!";
	print_r($payment);
	echo $ex->getMessage();
	echo $ex->getTraceAsString();
});