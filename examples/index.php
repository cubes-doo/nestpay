<?php

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/config.php';
($config);

$merchantService = new \Cubes\Nestpay\MerchantService($config);

require __DIR__ . '/dao.php';
$merchantService->setPaymentDao(new \PaymentDao('mysql:host=db;dbname=nestpay', 'root', 'root', [
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]));

$r = rand(1000, 9999);

$requestParameters = $merchantService->paymentMakeRequestParameters([
	'amount' =>  $r / 100,
	'currency' => \Cubes\Nestpay\Payment::CURRENCY_RSD,
	'lang' => 'sr',
	\Cubes\Nestpay\Payment::PROP_ENCODING => 'encoding',
	\Cubes\Nestpay\Payment::PROP_DESCRIPTION => 'description-' . $r,
	\Cubes\Nestpay\Payment::PROP_COMMENTS => 'comments-' . $r,
	\Cubes\Nestpay\Payment::PROP_EMAIL => 'email-' . $r,
	\Cubes\Nestpay\Payment::PROP_TEL => 'tel-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOCOMPANY => 'BillToCompany-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTONAME => 'BillToName-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOSTREET1 => 'BillToStreet1-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOSTREET2 => 'BillToStreet2-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOCITY => 'BillToCity-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOSTATEPROV => 'BillToStateProv-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOPOSTALCODE => 'BillToPostalCode-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOCOUNTRY => 'BillToCountry-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOCOMPANY => 'ShipToCompany-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTONAME => 'ShipToName-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOSTREET1 => 'ShipToStreet1-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOSTREET2 => 'ShipToStreet2-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOCITY => 'ShipToCity-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOSTATEPROV => 'ShipToStateProv-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOPOSTALCODE => 'ShipToPostalCode-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOCOUNTRY => 'ShipToCountry-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA1 => 'DimCriteria1-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA2 => 'DimCriteria2-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA3 => 'DimCriteria3-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA4 => 'DimCriteria4-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA5 => 'DimCriteria5-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA6 => 'DimCriteria6-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA7 => 'DimCriteria7-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA8 => 'DimCriteria8-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA9 => 'DimCriteria9-' . $r,
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA10 => 'DimCriteria10-' . $r,
]);
?>
<html>
	<body>
		<pre>
			<code>
				<?php print_r($merchantService->getWorkingPayment());?>
			</code>
		</pre>
		<form method="post" action="https://testsecurepay.intesasanpaolocard.com/fim/est3Dgate">
			<?php foreach ($requestParameters as $key => $value) {?>
			<input type="hidden" name="<?php echo htmlspecialchars($key);?>"  value="<?php echo htmlspecialchars($value);?>">
			<?php }?>
			<input type="submit" value="Start payment">
		</form>
	</body>
</html>