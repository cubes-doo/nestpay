<?php
require __DIR__ . '/bootstrap.php';

header('Content-Type: text/plain;charset=utf8');
//second parameter "true" is for failed page
$payment = $merchantService->paymentProcess3DGateResponse($_POST, true);
print_r($payment);