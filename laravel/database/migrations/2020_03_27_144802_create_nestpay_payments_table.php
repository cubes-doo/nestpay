<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNestpayPaymentsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'nestpay_payments';

    /**
     * Run the migrations.
     * @table nestpay_payments
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('processed')->default(0)->comment('1-processed; 0-not_processed');
            $table->char('oid', 64)->comment('Unique identifier of the order');
            $table->char('trantype', 20)->comment('Transaction type Set to "Auth" for authorization, “PreAuth” for preauthorization');
            $table->decimal('amount', 12, 2)->comment('amount transaction amount Use "." or "," as decimal separator, do not use grouping character');
            $table->char('currency', 3)->comment('ISO code of transaction currency ISO 4217 numeric currency code, 3 digits');
            $table->char('Response', 10)->nullable()->comment('Payment status. Possible values: "Approved", "Error", "Declined"');
            $table->char('ProcReturnCode', 2)->nullable()->comment('Transaction status code. “00” for authorized transactions, “99” for gateway errors, others for ISO-8583 error codes');
            $table->char('mdStatus', 3)->nullable()->comment('Status code for the 3D transaction. 1=authenticated transaction 2, 3, 4 = Card not participating or attempt 5,6,7,8 = Authentication not available or system error 0 = Authentication failed');
            $table->string('ErrMsg')->nullable()->comment('Error message');
            $table->char('AuthCode', 32)->nullable()->comment('Transaction Verification/Approval/Authoriza tion code');
            $table->string('TransId', 64)->nullable()->comment(' Nestpay Transaction Id');
            $table->string('TRANID', 64)->nullable()->comment(' Nestpay Transaction Id');
            $table->string('clientIp', 15)->nullable()->comment('IP address of the customer');
            $table->string('email', 64)->nullable()->comment('Customer\'s email address');
            $table->string('tel', 32)->nullable()->comment('Customer phone');
            $table->string('description')->nullable()->comment('Description sent to MPI');
            $table->string('BillToCompany')->nullable()->comment('BillTo company name');
            $table->string('BillToName')->nullable()->comment('BillTo name/surname');
            $table->string('BillToStreet1')->nullable()->comment('BillTo address line 1');
            $table->string('BillToStreet2')->nullable()->comment('BillTo address line 2');
            $table->string('BillToCity', 64)->nullable()->comment('BillTo city');
            $table->string('BillToStateProv', 32)->nullable()->comment('BillTo state/province');
            $table->string('BillToPostalCode', 32)->nullable()->comment('BillTo postal code');
            $table->string('BillToCountry', 32)->nullable()->comment('BillTo country code');
            $table->string('ShipToCompany')->nullable()->comment('ShipTo company');
            $table->string('ShipToName')->nullable()->comment('ShipTo name');
            $table->string('ShipToStreet1')->nullable()->comment('ShipTo address line 1');
            $table->string('ShipToStreet2')->nullable()->comment('ShipTo address line 2');
            $table->string('ShipToCity', 64)->nullable()->comment('ShipTo city');
            $table->string('ShipToStateProv', 32)->nullable()->comment('ShipTo state/province');
            $table->string('ShipToPostalCode', 32)->nullable()->comment('ShipTo postal code');
            $table->string('ShipToCountry', 32)->nullable()->comment('ShipTo country code');
            $table->string('DimCriteria1', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('DimCriteria2', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('DimCriteria3', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('DimCriteria4', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('DimCriteria5', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('DimCriteria6', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('DimCriteria7', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('DimCriteria8', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('DimCriteria9', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('DimCriteria10', 64)->nullable()->comment('Merchant specific parameter');
            $table->string('comments')->nullable()->comment('Kept as description for the transaction');
            $table->string('instalment', 3)->nullable()->comment('Instalment count');
            $table->string('INVOICENUMBER')->nullable()->comment('Invoice Number');
            $table->string('storetype', 16)->nullable()->comment('Merchant payment model Possible values: "pay_hosting", “3d_pay”, "3d", "3d_pay_hosting"');
            $table->string('lang', 16)->nullable()->comment('Language of the payment pages hosted by NestPay');
            $table->string('xid')->nullable()->comment('Internet transaction identifier');
            $table->string('HostRefNum')->nullable()->comment('Host reference number ');
            $table->string('ReturnOid', 64)->nullable()->comment('Returned order ID, must same as input orderId');
            $table->char('MaskedPan', 20)->nullable()->comment('Masked credit card number');
            $table->char('rnd', 20)->nullable()->comment('Random string, will be used for hash comparison');
            $table->string('merchantID')->nullable()->comment('MPI merchant ID');
            $table->string('txstatus')->nullable()->comment('3D status for archival Possible values "A", "N", "Y"');
            $table->string('iReqCode')->nullable()->comment('Code provided by ACS indicating data that is formatted correctly, but which invalidates the request. This element is included when business processing cannot be performed for some reason.');
            $table->string('iReqDetail')->nullable()->comment('May identify the specific data elements that caused the Invalid Request Code (so never supplied if Invalid Request Code is omitted).');
            $table->string('vendorCode')->nullable()->comment('Error message describing iReqDetail error.');
            $table->string('PAResSyntaxOK')->nullable()->comment('If PARes validation is syntactically correct, the value is true. Otherwise value is false. "Y" or "N"');
            $table->string('PAResVerified')->nullable()->comment('If signature validation of the return message is successful, the value is true. If PARes message is not received or signature validation fails, the value is false. "Y" or "N"');
            $table->string('eci')->nullable()->comment('Electronic Commerce Indicator. empty for non-3D transactions');
            $table->string('cavv')->nullable()->comment('Cardholder Authentication Verification Value, determined by ACS. 28 characters, contains a 20 byte value that has been Base64 encoded, giving a 28 byte result.');
            $table->string('cavvAlgorthm')->nullable()->comment('CAVV algorithm Possible values "0", "1", "2", "3"');
            $table->string('md')->nullable()->comment('MPI data replacing card number');
            $table->string('Version')->nullable()->comment('MPI version information 3 characters l(ike "2.0")');
            $table->string('sID')->nullable()->comment('Schema ID "1" for Visa, "2" for Mastercard');
            $table->text('mdErrorMsg')->nullable()->comment('Error Message from MPI (if any)');
            $table->string('clientid')->nullable();
            $table->string('EXTRA_TRXDATE')->nullable();
            $table->string('ACQBIN')->nullable();
            $table->string('acqStan')->nullable();
            $table->string('cavvAlgorithm')->nullable();
            $table->string('digest')->nullable();
            $table->string('dsId')->nullable();
            $table->string('Ecom_Payment_Card_ExpDate_Month')->nullable();
            $table->string('Ecom_Payment_Card_ExpDate_Year')->nullable();
            $table->string('EXTRA_CARDBRAND')->nullable();
            $table->string('EXTRA_CARDISSUER')->nullable();
            $table->string('EXTRA_INVOICENUMBER')->nullable();
            $table->string('failUrl')->nullable();
            $table->string('HASH')->nullable();
            $table->string('hashAlgorithm')->nullable();
            $table->string('HASHPARAMS')->nullable();
            $table->string('HASHPARAMSVAL')->nullable();
            $table->string('okurl')->nullable();
            //$table->string('payResults.dsId')->nullable();
            $table->string('refreshtime')->nullable();
            $table->string('SettleId')->nullable();

            $table->index('AuthCode', 'AuthCode');
            $table->index('processed', 'processed');
            $table->index('trantype', 'trantype');
            $table->index('currency', 'currency');
            $table->index('Response', 'Response');
            $table->index('ProcReturnCode', 'ProcReturnCode');
            $table->index('mdStatus', 'mdStatus');

            $table->unique('oid', 'oid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}
