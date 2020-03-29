<?php
require __DIR__ . '/../vendor/autoload.php';

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

$oid = isset($_POST['oid']) ? $_POST['oid'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : null;
?>
<form method="post" action="">
OID: <input type="text" name="oid" value="<?php echo htmlspecialchars($oid);?>">
<br>
Amount: <input type="text" name="amount" value="<?php echo htmlspecialchars($amount);?>" palceholder="Leave blank to capture total amount">
<button type="submit">Process</button>
</form>
<hr>
<?php
if ($oid) {
$result = $merchantService->postAuthorizationOverNestpayApi($oid, $amount);

print_r($result);
}
