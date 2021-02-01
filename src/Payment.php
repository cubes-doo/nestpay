<?php

namespace Cubes\Nestpay;

interface Payment extends \ArrayAccess, \JsonSerializable
{

	const TRAN_TYPE_AUTH = 'Auth';
	const TRAN_TYPE_PREAUTH = 'PreAuth';

	const RESPONSE_APPROVED = 'Approved';
	const PROC_RESPONSE_CODE_APPROVED = '00';

	const CURRENCY_EURO = '978';
	const CURRENCY_EUR = '978';
	const CURRENCY_USD = '840';
	const CURRENCY_GBP = '826';
	const CURRENCY_CNY = '156';
	const CURRENCY_RUB = '643';
	const CURRENCY_RSD = '941';
	const CURRENCY_MKD = '807';

	const DEFAULT_LANG = 'en';
	const DEFAULT_CURRENCY = '840';

	//Helper constants for properties
	const PROP_TRANTYPE = 'trantype';
	const PROP_AMOUNT = 'amount';
	const PROP_CURRENCY = 'currency';
	const PROP_OID = 'oid';
	const PROP_LANG = 'lang';
	const PROP_RND = 'rnd';
	const PROP_ENCODING = 'encoding';
	const PROP_DESCRIPTION = 'description';
	const PROP_COMMENTS = 'comments';
	const PROP_EMAIL = 'email';
	const PROP_TEL = 'tel';
	const PROP_BILLTOCOMPANY = 'BillToCompany';
	const PROP_BILLTONAME = 'BillToName';
	const PROP_BILLTOSTREET1 = 'BillToStreet1';
	const PROP_BILLTOSTREET2 = 'BillToStreet2';
	const PROP_BILLTOCITY = 'BillToCity';
	const PROP_BILLTOSTATEPROV = 'BillToStateProv';
	const PROP_BILLTOPOSTALCODE = 'BillToPostalCode';
	const PROP_BILLTOCOUNTRY = 'BillToCountry';
	const PROP_SHIPTOCOMPANY = 'ShipToCompany';
	const PROP_SHIPTONAME = 'ShipToName';
	const PROP_SHIPTOSTREET1 = 'ShipToStreet1';
	const PROP_SHIPTOSTREET2 = 'ShipToStreet2';
	const PROP_SHIPTOCITY = 'ShipToCity';
	const PROP_SHIPTOSTATEPROV = 'ShipToStateProv';
	const PROP_SHIPTOPOSTALCODE = 'ShipToPostalCode';
	const PROP_SHIPTOCOUNTRY = 'ShipToCountry';
	const PROP_DIMCRITERIA1 = 'DimCriteria1';
	const PROP_DIMCRITERIA2 = 'DimCriteria2';
	const PROP_DIMCRITERIA3 = 'DimCriteria3';
	const PROP_DIMCRITERIA4 = 'DimCriteria4';
	const PROP_DIMCRITERIA5 = 'DimCriteria5';
	const PROP_DIMCRITERIA6 = 'DimCriteria6';
	const PROP_DIMCRITERIA7 = 'DimCriteria7';
	const PROP_DIMCRITERIA8 = 'DimCriteria8';
	const PROP_DIMCRITERIA9 = 'DimCriteria9';
	const PROP_DIMCRITERIA10 = 'DimCriteria10';
	const PROP_INVOICENUMBER = 'INVOICENUMBER';


	const PROP_RESPONSE = 'Response';
	const PROP_AUTHCODE = 'AuthCode';
	const PROP_PROCRETURNCODE = 'ProcReturnCode';
	const PROP_TRANSID = 'TransId';
	const PROP_ERRMSG = 'ErrMsg';
	const PROP_MDSTATUS = 'mdStatus';

	const ALLOWED_PROPERTIES = [
		'processed',
		'oid',
		'trantype',
		'currency',
		'amount',
		'Response',
		'AuthCode',
		'ProcReturnCode',
		'TransId',
		'ErrMsg',
		'mdStatus',
		'email',
		'tel',
		'description',
		'BillToCompany',
		'BillToName',
		'BillToStreet1',
		'BillToStreet2',
		'BillToCity',
		'BillToStateProv',
		'BillToPostalCode',
		'BillToCountry',
		'ShipToCompany',
		'ShipToName',
		'ShipToStreet1',
		'ShipToStreet2',
		'ShipToCity',
		'ShipToStateProv',
		'ShipToPostalCode',
		'ShipToCountry',
		'DimCriteria1',
		'DimCriteria2',
		'DimCriteria3',
		'DimCriteria4',
		'DimCriteria5',
		'DimCriteria6',
		'DimCriteria7',
		'DimCriteria8',
		'DimCriteria9',
		'DimCriteria10',
		'instalment',
		'INVOICENUMBER',
		'storetype',
		'lang',
		'xid',
		'HostRefNum',
		'clientIp',
		'ReturnOid',
		'MaskedPan',
		'rnd',
		'merchantID',
		'txstatus',
		'iReqCode',
		'iReqDetail',
		'vendorCode',
		'PAResSyntaxOK',
		'PAResVerified',
		'eci',
		'cavv',
		'cavvAlgorthm',
		'md',
		'Version',
		'SID',
		'mdErrorMsg',
		'TRANID',
		'clientid',
		'EXTRA_TRXDATE',
		'comments',

		'ACQBIN',
		'acqStan',
		'cavvAlgorithm',
		'digest',
		'dsId',
		'Ecom_Payment_Card_ExpDate_Month',
		'Ecom_Payment_Card_ExpDate_Year',
		'EXTRA_CARDBRAND',
		'EXTRA_CARDISSUER',
		'EXTRA_INVOICENUMBER',
		'failUrl',
		'HASH',
		'hashAlgorithm',
		'HASHPARAMS',
		'HASHPARAMSVAL',
		'okurl',
		//'payResults.dsId'
		'refreshtime',
		'SettleId',
	];

	/**
	 * @return scalar
	 */
	public static function generateOid();

	/**
	 * @return scalar
	 */
	public static function generateRnd();

	/**
	 * 
	 * @param string $key
	 * @param scalar $value
	 * @return \Cubes\Nestpay\Payment
	 * @throws \InvalidArgumentException
	 */
	public function setProperty($key, $value);

	public function getProperty($key);

	/** 
	 * @param array $properties
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setProperties(array $properties);

	/**
	 * @param array $keys
	 * @param array $excludeKeys
	 * @return array
	 */
	public function getProperties(array $keys = null, array $excludeKeys = null, $onlyNonEmpty = false);

	/**
	 * @return array
	 */
	public function toArray();

	/**
	 * @return scalar
	 */
	public function getOid();

	/**
	 * @param salar $oid
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setOid($oid);

	/**
	 * @return scalar
	 */
	public function getRnd();

	/**
	 * @param salar $rnd
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setRnd($rnd);

	/**
	 * @return scalar
	 */
	public function getCurrency();

	/**
	 * @param salar $currency
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setCurrency($currency);

	/**
	 * @return scalar
	 */
	public function getLang();

	/**
	 * @param salar $lang
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setLang($lang);

	/**
	 * @return float
	 */
	public function getAmount();

	/**
	 * @param float $amount
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setAmount($amount);

	/**
	 * @return string
	 */
	public function getEmail();

	/**
	 * @param float $email
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setEmail($email);

	/**
	 * @return scalar
	 */
	public function getTrantype();

	/**
	 * @param string $Trantype
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setTrantype($Trantype);

	/**
	 * @return scalar
	 */
	public function getInstalment();

	/**
	 * @param scalar $instalment
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setInstalment($instalment);

	/**
	 * @return int
	 */
	public function getProcessed();

	/**
	 * @param int $processed
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setProcessed($processed);

	/**
	 * @return boolean
	 */
	public function isProcessed();

	/**
	 * 
	 * @return boolean
	 */
	public function isSuccess();
}
