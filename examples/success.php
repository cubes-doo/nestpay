<?php
require __DIR__ . '/bootstrap.php';


header('Content-Type: text/plain;charset=utf8');
$payment = $merchantService->paymentProcess3DGateResponse($_POST);
print_r($payment);

