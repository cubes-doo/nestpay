# Cubes Nestpay
Nestpay E-commerce integration

[![Latest Stable Version](https://poser.pugx.org/cubes-doo/nestpay/v/stable)](https://packagist.org/packages/cubes-doo/nestpay) [![Total Downloads](https://poser.pugx.org/cubes-doo/nestpay/downloads)](https://packagist.org/packages/cubes-doo/nestpay) [![License](https://poser.pugx.org/cubes-doo/nestpay/license)](https://packagist.org/packages/cubes-doo/nestpay)

## Default usage

### Installation

Require this package with composer.

```shell
composer require cubes-doo/nestpay
```

For this package to work you need table **nestpay_payments** in database.
This table is used to store information about payments.

You could create table by importing example SQL script:

```shell
mysql -u root -p your_db_name < vendor/cubes-doo/nestpay/resources/nestpay_payments.sql
```

### Bootstrap & Configuration

Main class to use is **\Cubes\Nestpay\MerchantService**

Instanciate the class and pass configuration parameters:

```php
use Cubes\Nestpay\MerchantService;

//...

$nestpayMerchantService = new MerchantService([
    'clientId' => '********',
    'storeKey' => '********',
    'storeType' => '3D_PAY_HOSTING',
    'okUrl' => 'http://localhost:8082/examples/success.php',
    'failUrl' => 'http://localhost:8082/examples/failed.php',
    '3DGateUrl' => 'https://testsecurepay.eway2pay.com/fim/est3Dgate',

    //API
    'apiName' => '********',
    'apiPassword' => '********',
    'apiEndpointUrl' => 'https://testsecurepay.eway2pay.com/fim/api'
]);

```


Setup the connection to the database by using existing PDO instance

```php
$nestpayMerchantService->setPDO($pdo); //$pdo is instanceof \PDO
```


If you have named table **nestpay_payments** something else you should pass another parameter

```php
$nestpayMerchantService->setPDO($pdo, 'your_table'); //'your_table' is name of the table for payments
```

Configure **\Cubes\Nestpay\MerchantService** what to do when successful payment occurres or what to do on failed payment:

```php
$merchantService->onFailedPayment(function ($payment) {
    //$payment is instance of \Cubes\Nestpay\Payment

    //send an email for failed payment attempt
    // $email = $payment->getProperty(\Cubes\Nestpay\Payment::PROP_EMAIL);
    // $customerName = $payment->getProperty(\Cubes\Nestpay\Payment::PROP_BILLTONAME);

})->onSuccessfulPayment(function($payment) {
	//$payment is instance of \Cubes\Nestpay\Payment

    //send an email for successful payment
    // $email = $payment->getProperty(\Cubes\Nestpay\Payment::PROP_EMAIL);
    // $customerName = $payment->getProperty(\Cubes\Nestpay\Payment::PROP_BILLTONAME);

    //do stuff related to the siccessfull payment
});
```

### Usage

#### The confirmation page
You should have confirmation page from which customers are redirected to the bank card processor page.

That page should have form with lots of hidden paramters.

Use **\Cubes\Nestpay\MerchantService::paymentMakeRequestParameters** method to generate necessary parameters

```php
<?php 

$requestParameters = $nestpayMerchantService->paymentMakeRequestParameters([
	'amount' =>  123.45,
	'currency' => \Cubes\Nestpay\Payment::CURRENCY_RSD,
	'lang' => 'sr',
	\Cubes\Nestpay\Payment::PROP_TRANTYPE => \Cubes\Nestpay\Payment::TRAN_TYPE_PREAUTH,
	\Cubes\Nestpay\Payment::PROP_EMAIL => 'john.doe@example.com',
	\Cubes\Nestpay\Payment::PROP_BILLTONAME => 'John Doe',
	//below are not mandatory parameters
	\Cubes\Nestpay\Payment::PROP_BILLTOSTREET1 => 'BillToStreet1',
	\Cubes\Nestpay\Payment::PROP_BILLTOSTREET2 => 'BillToStreet2',
	\Cubes\Nestpay\Payment::PROP_BILLTOCITY => 'BillToCity',
	\Cubes\Nestpay\Payment::PROP_BILLTOSTATEPROV => 'BillToStateProv',
	\Cubes\Nestpay\Payment::PROP_BILLTOPOSTALCODE => 'BillToPostalCode',
	\Cubes\Nestpay\Payment::PROP_BILLTOCOUNTRY => 'RS',
	\Cubes\Nestpay\Payment::PROP_SHIPTOCOMPANY => 'ShipToCompany',
	\Cubes\Nestpay\Payment::PROP_SHIPTONAME => 'ShipToName',
	\Cubes\Nestpay\Payment::PROP_SHIPTOSTREET1 => 'ShipToStreet1',
	\Cubes\Nestpay\Payment::PROP_SHIPTOSTREET2 => 'ShipToStreet2',
	\Cubes\Nestpay\Payment::PROP_SHIPTOCITY => 'ShipToCity',
	\Cubes\Nestpay\Payment::PROP_SHIPTOSTATEPROV => 'ShipToStateProv',
	\Cubes\Nestpay\Payment::PROP_SHIPTOPOSTALCODE => 'ShipToPostalCode',
	\Cubes\Nestpay\Payment::PROP_SHIPTOCOUNTRY => 'RS',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA1 => 'DimCriteria1',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA2 => 'DimCriteria2',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA3 => 'DimCriteria3',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA4 => 'DimCriteria4',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA5 => 'DimCriteria5',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA6 => 'DimCriteria6',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA7 => 'DimCriteria7',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA8 => 'DimCriteria8',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA9 => 'DimCriteria9',
	\Cubes\Nestpay\Payment::PROP_DIMCRITERIA10 => 'DimCriteria10',
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
?>
```

After submitting this form customer is redirected to 3D gate bank card processing page where custoer enters Card Number, CVC etc.

#### The result page

After entering Card details, customer is redirected back to your website on success or fail url.

You should call **\Cubes\Nestpay\MerchantService::paymentProcess3DGateResponse** method to process $_POST parameters.

On success page:

```php
<?php
$payment = $nestpayMerchantService->paymentProcess3DGateResponse($_POST);
//DO NOT SEND EMAIL HERE OR DO SOME ACTION ON SUCCESSFUL PAYMENT, JUST SHOW RESULT!
//USE $nestpayMerchantService->onSuccessfulPayment INSTEAD!!!
//display results of the payment:
?>

<h1>Your payment <?php $payment->isSuccess() ? 'is successful' : 'has failed'?></h1>

```

On faile page:

```php
<?php
//second parameter (true) indicates that this processing is on fail url
$payment = $nestpayMerchantService->paymentProcess3DGateResponse($_POST, true); 

//display resultsu of the payment:
?>

<h1>Your payment <?php $payment->isSuccess() ? 'is successful' : 'has failed'?></h1>

```

#### Processing payment over API

When customer leaves the 3D Gate Page there is a possibility that he/she is NOT going to be redirected back to your website (internet connection broke, customer closes the browser etc.).

You should use **\Cubes\Nestpay\MerchantService::paymentProcessOverNestpayApi** method to process payment over API in some cron job.

```php
//$oid is the OID of the payment
$payment = $nestpayMerchantService->paymentProcessOverNestpayApi($oid); 

//DO NOT SEND EMAIL HERE OR DO SOME ACTION ON SUCCESSFUL PAYMENT!
//USE $nestpayMerchantService->onSuccessfulPayment INSTEAD!!!

```

#### Capture payment (PostAuth) over API

For two step payment (PreAuth and PostAuth) you should use Nestpay API to capture reserved amount of successful payment.

Use **\Cubes\Nestpay\MerchantService::postAuthorizationOverNestpayApi**

```php
//$oid is the OID of the payment
$result = $nestpayMerchantService->postAuthorizationOverNestpayApi($oid); 
```

If you DO NOT want to capture entire amount, pass second parameter.
```php
//$oid is the OID of the payment
//$amount should not be greated than the orginal amount reserved in PreAuth
$result = $nestpayMerchantService->postAuthorizationOverNestpayApi($oid, $amount); 
```

#### Void payment  over API

To void payment use **\Cubes\Nestpay\MerchantService::voidOverNestpayApi**

```php
//$oid is the OID of the payment
$result = $nestpayMerchantService->voidOverNestpayApi($oid); 
```

### Customize saving payment information

If you prefer some other method to store payments, you should create "Data Access Object" class of your own.
Your DAO class must implement **\Cubes\Nestpay\PaymentDao** interface:

```php
use \Cubes\Nestpay\PaymentDao;
use \Cubes\Nestpay\Payment;

class MyPaymentDao implements PaymentDao
{
    /**
	 * Fetch payment by $oid
	 * 
	 * @return \Cubes\Nestpay\Payment
	 * @param scalar $oid
	 */
    public function getPayment($oid)
    {
        //return payment by oid
    }
	
	/**
	 * Saves the payment
	 * 
	 * @param \Cubes\Nestpay\Payment $payment
	 * @return \Cubes\Nestpay\Payment
	 */
    public function savePayment(Payment $payment)
    {
        //save existing payment
    }

	/**
	 * Creates new payment
	 *
	 * @param array $properties
	 * @return \Cubes\Nestpay\Payment
	 */
    public function createPayment(array $properties)
    {
        //create new payment
    }
}
```

Make **\Cubes\Nestpay\MerchantService** use your DAO class

```php
$nestpayMerchantService->setPaymentDao(new MyPaymentDao());
```