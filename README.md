# Cubes Nestpay
Nestpay E-commerce integration, shipped with Laravel Package

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

*If you are using Laravel there is a ready to use migration (see documentation below)


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
    'okUrl' => 'http://localhost:8082/examples/success.php', //this could be configured later
    'failUrl' => 'http://localhost:8082/examples/failed.php', //this could be configured later
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


If you want to have some other name for the **nestpay_payments** table, something you should pass another parameter

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

**IMPORTANT NOTICE!!!**
The **onSuccessfulPayment** and **onFailedPayment** could be triggered in 2 ways

- By calling **\Cubes\Nestpay\MerchantService::paymentProcess3DGateResponse** when customer is redirected back to you page (see documentation below)
- By calling **\Cubes\Nestpay\MerchantService::paymentProcessOverNestpayApi** from your cron job (see documentation below)

For that reason it is important to write your logic, like sending email or changing the order status, for successuful or failed payment IN THIS HANDLERS!!! (so you don't have to write it twice)

### Usage

#### The confirmation page
You should have confirmation page from which customers are redirected to the bank card processor page.

That page should have form with lots of hidden paramters.

Use **\Cubes\Nestpay\MerchantService::paymentMakeRequestParameters** method to generate necessary parameters (the HASH parameter and other necessary parameters)

```php
<?php 

$requestParameters = $nestpayMerchantService->paymentMakeRequestParameters([
    'amount' =>  123.45,
    'currency' => \Cubes\Nestpay\Payment::CURRENCY_RSD,
    'lang' => 'sr',
    //set transaction type to PreAuth or Auth
    \Cubes\Nestpay\Payment::PROP_TRANTYPE => \Cubes\Nestpay\Payment::TRAN_TYPE_PREAUTH,
    //this is email of the customer
    \Cubes\Nestpay\Payment::PROP_EMAIL => 'john.doe@example.com',
    
    //below are optional parameters
    \Cubes\Nestpay\Payment::PROP_INVOICENUMBER => '123456789', //must be numeric!!
    \Cubes\Nestpay\Payment::PROP_BILLTONAME => 'John Doe',\Cubes\Nestpay\Payment::PROP_BILLTOSTREET1 => 'BillToStreet1',
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

After submitting this form customer is redirected to 3D gate bank card processing page where customer enters Card Number, CVC etc.

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

On fail page:

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
//$oid is the OID of some unprocessed payment (WHERE `processed` != 1)
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

### Get working payment

If you want to get the last processed payment use **\Cubes\Nestpay\MerchantService::getWorkingPayment**

```php

//$payment is instance of \Cubes\Nestpay\Payment 
$payment = $nestpayMerchantService->getWorkingPayment();

//get some of the payment properties
$email = $payment->getProperty(\Cubes\Nestpay\Payment::PROPERTY_EMAIL);

//for some important properties there are getters
$email = $payment->getEmail();

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

## Integration with Laravel Framework(>=5.4)

Package `cubes-doo/nestpay` comes with built Laravel package.

Service provider class is **\Cubes\Nestpay\Laravel\NestpayServiceProvider**.
###### If you are using Laravel version < 5.5 you must include service provider manually

```php
<?php
//THIS IS config/app.php

return [
    
    //got to providers key
    // ...

    'providers' => [
        //...

        \Cubes\Nestpay\Laravel\NestpayServiceProvider::class
    ],

    'aliases' => [
        //...
        // optinally add alias for facade
        'Nestpay' => \Cubes\Nestpay\Laravel\Facade::class,
    ],
];
```

Before using the \Cubes\Nestpay\MerchantService class you should **edit your .env file**:
```shell
#add this to your .env file and set you clientID storeKey etc
NESTPAY_MERCHANT_CLIENT_ID=********
NESTPAY_MERCHANT_STORE_KEY=********
NESTPAY_MERCHANT_3DGATE_URL=https://testsecurepay.eway2pay.com/fim/est3Dgate
NESTPAY_MERCHANT_API_NAME=*******
NESTPAY_MERCHANT_API_PASSWORD=*******
NESTPAY_MERCHANT_API_ENDPOINT_URL=https://testsecurepay.eway2pay.com/fim/api
```

The package provides **\Cubes\Nestpay\MerchantService** class which could be injected in controllers and other points in Laravel application:

```php
namespace App\Http\Controllers;

use \Cubes\Nestpay\MerchantService;

class TestController extends Controller
{
    public function index(MerchantService $merchantService)
    {

    }
}
```

Also **\Cubes\Nestpay\MerchantService** could be obtained using facade or service container:

```php

//Using facade
\Nestpay::paymentProcess3DGateResponse($request->all());

//using service container with "nestpay" key
app('nestpay')->paymentProcessOverNestpayApi($nestpayPayment->oid);
```

For unprocessed payments (when customer does not navigate back to your site after payment by accident) there is also available artisan command:

```shell
#this command will call Nestpay API to get payment result unprocessed payments
php artisan nestpay:handle-unprocessed-payments
```

### Laravel resources (config, controllers, views , etc)

Although you could integrate Nestpay service into your Laravel application manually, this Laravel package has all you need to integrate Nestpay system.

1. Publish package resources into your Laravel application:

```php
php artisan vendor:publish --provider="Cubes\\Nestpay\\Laravel\\NestpayServiceProvider"

```

2. Customize published config file **config/nestpay.php** to your production parameters (keep your testing parameters in .env)

```php
//file: config/nestpay.php
return [
    'merchant' => [/* the mercant configuration*/],
   
    //change this if you want to use some other class for payment model
    //Object of paymentModel class is going to be returned when calling MerchantService::getWorkingPayment
     'paymentModel' => \App\Models\NestpayPayment::class 
    //...
];
```
3. Add Nestpay routes among others
```php
//file: routes/web.php

\Nestpay::routes();

```

4. Add published event subscriber into your **\App\Providers\EventServiceProvider**

```php
//file: app/Providers/EventServiceProvider.php

    protected $subscribe = [
        'App\Listeners\NestpayEventsSubscriber',
    ];
```

5. Customize published migration for `nestpay_payment` table
```php
//file: database/migrations/2020_03_27_144802_create_nestpay_payments_table.php

public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('processed')->default(0)->comment('1-processed; 0-not_processed');
            $table->char('oid', 64)->comment('Unique identifier of the order');

            // add your application specific fields like user_id or order_id etc..

            //DO NOT REMOVE ANY OF EXISTING COLUMNS!!!
```

6. Customize published model **\App\Models\NestpayPayment**

```php
//file: app\Migrations\NestpayPayment

namespace App\Models;

use Cubes\Nestpay\Laravel\PaymentModel as Model;

class NestpayPayment extends Model
{
    protected $table = 'nestpay_payments';

    protected $fillable = [

        //DO NOT REMOVE ANY FILLABLES JUST ADD NEW ONE FOOUR APPLICATION
        'processed',
        'oid',
        'trantype', 
        //...
```
7. Customize controller **\App\Http\Controllers\NestpayController**

```php
class NestpayController extends Controller
{
    ...

    //You should definitively start from this point
    //customize how to read amount, currenct customer email and other stuff from your application
    protected function getPaymentData()
    {
        //...
    }
}
```

**IMPORTANT NOTICE!!!**
You SHOULD NOT send emails to customer or have any logic which is related to the successful payment in this controller, because payment could be processed also over nestpay::handle-unprocessed-payments artisan command!

USE **NestpayEventsSubscriber** instead (see documentation below).

**IMPORTANT NOTICE!!!**
Routes to actions **NestpayController@success** and **NestpayController@fail** MUST BE EXCLUDED FROM CSRF TOKEN VERIFICATION, exclude urls for that actions by editing **VerifyCsrfTokent** middleware

```php
//file: app/Http/Middleware/VerifyCsrfToken.php

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/nestpay/success', //change this if you have customized routes
        '/nestpay/fail', //change this if you have customized routes
    ];
}
```

8. Customize view scripts

```
resources   
│
└───views
│   │
│   └───vendor
│       │   
│       │───nestpay
│       |       │
│       |       │    confirm.blade.php
│       |       │    email.blade.php
│       |       │    result.blade.php

```

9. Schedule **nestpay::handle-unprocessed-payments** command to execute every five minutes, so unproccessed payments could be handled over Nestpay API in background

```php
//file: app/Console/Kernel.php

class Kernel extends ConsoleKernel
{
    //...

    
    protected function schedule(Schedule $schedule)
    {
        //...
        $schedule->command('nestpay::handle-unprocessed-payments')->everyFiveMinutes();
    }

    //...
```
10. Customize listener **\App\Listeners\NestpayEventsSubscriber** 

**IMPORTANT NOTICE!!!** 

THIS IS THE MOST IMPORTANT CUSTOMIZATION!!!

When payment is processed (eather over NestpayController or nestpay::handle-unprocessed-payments command) the following events are triggered:

- \Cubes\Nestpay\Laravel\NestpayPaymentProcessedSuccessfullyEvent - for successful payments
- \Cubes\Nestpay\Laravel\NestpayPaymentProcessedFailedEvent - for failed events

At this point you should have the published event subscriber **\App\Listeners\NestpayEventsSubscriber**  which is configured to listen to those events.

The class has logic for sending necessary mail to the customer, all you have to do is add logic when payment has been successfull (when custmer HAS PAID)

```php

//file: app/Listeners/NestpayEventsSubscrber
class NestpayEventsSubscriber
{
    /**
     * Successfull payment
     */
    public function nestpayPaymentProcessedSuccessfullyEvent(NestpayPaymentProcessedSuccessfullyEvent $event) {
        $payment = $event->getPayment();

        //CUSTOMER HAS PAID, DO RELATED STUFF HERE

        //$payment is instanceof Eloquent Model which implements \Cubes\Nestpay\Payment interface

        //sending email
        \Mail::to(
            $payment->getProperty(Payment::PROP_EMAIL),
            $payment->getProperty(Payment::PROP_BILLTONAME)
        )->send(new NestpayPaymentMail($payment));
    }

    //...
```

