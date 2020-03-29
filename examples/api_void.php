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
?>
<form method="post" action="">
OID: <input type="text" name="oid" value="<?php echo htmlspecialchars($oid);?>">
<button type="submit">Process</button>
</form>
<hr>
<?php
if ($oid) {
$result = $merchantService->voidOverNestpayApi($oid);

print_r($result);
}
