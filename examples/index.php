<?php

require __DIR__ . '/bootstrap.php';

$r = rand(1000, 9999);

$requestParameters = $merchantService->paymentMakeRequestParameters([
	'amount' =>  $r / 100,
	'currency' => \Cubes\Nestpay\Payment::CURRENCY_RSD,
	'lang' => 'sr',
	//set transaction type to PreAuth or Auth
    \Cubes\Nestpay\Payment::PROP_TRANTYPE => \Cubes\Nestpay\Payment::TRAN_TYPE_PREAUTH,
	//this is email of the customer
    \Cubes\Nestpay\Payment::PROP_EMAIL => 'email-' . $r,
	
	\Cubes\Nestpay\Payment::PROP_INVOICENUMBER => $r, //MUST BE NUMBER!!!
	\Cubes\Nestpay\Payment::PROP_DESCRIPTION => 'description-' . $r,
	\Cubes\Nestpay\Payment::PROP_COMMENTS => 'comments-' . $r,
	\Cubes\Nestpay\Payment::PROP_TEL => 'tel-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTONAME => 'BillToName-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOCOMPANY => 'BillToCompany-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOSTREET1 => 'BillToStreet1-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOSTREET2 => 'BillToStreet2-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOCITY => 'BillToCity-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOSTATEPROV => 'BillToStateProv-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOPOSTALCODE => 'BillToPostalCode-' . $r,
	\Cubes\Nestpay\Payment::PROP_BILLTOCOUNTRY => 'RS',
	\Cubes\Nestpay\Payment::PROP_SHIPTOCOMPANY => 'ShipToCompany-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTONAME => 'ShipToName-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOSTREET1 => 'ShipToStreet1-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOSTREET2 => 'ShipToStreet2-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOCITY => 'ShipToCity-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOSTATEPROV => 'ShipToStateProv-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOPOSTALCODE => 'ShipToPostalCode-' . $r,
	\Cubes\Nestpay\Payment::PROP_SHIPTOCOUNTRY => 'RS',
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
		<form method="post" action="<?php echo $merchantService->get3DGateUrl();?>">
			<?php foreach ($requestParameters as $key => $value) {?>
			<input type="hidden" name="<?php echo htmlspecialchars($key);?>"  value="<?php echo htmlspecialchars($value);?>">
			<?php }?>
			<input type="submit" value="Start payment">
		</form>
	</body>
</html>