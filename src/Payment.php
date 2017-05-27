<?php

namespace Cubes\Nestpay;

class Payment implements \ArrayAccess, \JsonSerializable {
	
	const TRAN_TYPE_AUTH = 'Auth';
	const TRAN_TYPE_PREAUTH = 'PreAuth';
	
	const RESPONSE_APPROVED = 'Approved';
	const PROC_RESPONSE_CODE_APPROVED = '00';
	
	const CURRENCY_EURO = '978';
	const CURRENCY_RSD = '941';
	const CURRENCY_USD = '840';
	
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

	protected static $allowedProperties = [
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
		'comments'
	];
	
	public static function getAllowedProperties() {
		return self::$allowedProperties;
	}
	
	/**
	 * @return scalar
	 */
	public static function generateOid() {
		//UUID 4
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,
			// 48 bits for "node"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}
	
	/**
	 * @return scalar
	 */
	public static function generateRnd() {
		return mt_rand(1000000000, 2000000000);
	}
	
	
	/**
	 * @var array 
	 */
	protected $properties;
	
	
	//Payment methods
	public function __construct(array $properties = null) {
		$this->properties = [
			'processed' => 0,
			'oid' => self::generateOid(),
			'rnd' => self::generateRnd(),
			'currency' => self::DEFAULT_CURRENCY,
			'lang' => self::DEFAULT_LANG,
			'amount' => 0.01,
			'trantype' => self::TRAN_TYPE_AUTH,
		];
		
		if (is_array($properties)) {
			$this->setProperties($properties);
		}
	}
	
	//JsonSeriazable methods
	public function jsonSerialize() {
		return $this->getProperties();
	}

	//ArrayAccess methods
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->properties);
	}

	public function offsetGet($offset) {
		return $this->getProperty($offset);
	}

	public function offsetSet($offset, $value) {
		return $this->setProperty($offset, $value);
	}

	public function offsetUnset($offset) {
		if (isset($this->properties[$offset])) {
			unset($this->properties[$offset]);
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @param scalar $value
	 * @return \Cubes\Nestpay\Payment
	 * @throws \InvalidArgumentException
	 */
	public function setProperty($key, $value) {
		$setter = 'set' . ucfirst($key);
		if (method_exists($this, $setter)) {
			return $this->$setter($value);
		}
		
		if (!is_null($value) && !is_scalar($value)) {
			throw new \InvalidArgumentException('Argument $value must be scalar got ' . (is_array($value) ? 'array' : (is_null($value) ? 'null' : get_class($value))));
		}
		
		if (!in_array($key, self::$allowedProperties)) {
			return $this;
		}
		
		$this->properties[$key] = $value;
		
		return $this;
	}
	
	public function getProperty($key) {
		$getter = 'get' . ucfirst($key);
		if (method_exists($this, $getter)) {
			return $this->$getter();
		}
		
		return isset($this->properties[$key]) ? $this->properties[$key] : null;
	}
	
	/** 
	 * @param array $properties
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setProperties(array $properties) {
		foreach ($properties as $key => $val) {
			$this->setProperty($key, $val);
		}
		
		return $this;
	}
	
	/**
	 * @param array $keys
	 * @param array $excludeKeys
	 * @return array
	 */
	public function getProperties(array $keys = null, array $excludeKeys = null, $onlyNonEmpty = false) {
		if (!is_array($keys)) {
			$properties = $this->properties;
		} else {
		
			$properties = [];

			foreach ($keys as $key) {
				$value = $this->getProperty($key);

				$properties[$key] = $value;
			}
		}
		
		if ($excludeKeys) {
			foreach ($excludeKeys as $key) {
				unset($properties[$key]);
			}
		}
		
		if ($onlyNonEmpty) {
			foreach ($properties as $key => $val) {
				if (!is_numeric($val) && empty($val)) {
					unset($properties[$key]);
				}
			}
		}
		
		return $properties;
	}
	
	/**
	 * @return array
	 */
	public function toArray() {
		return $this->getProperties();
	}
	
	/**
	 * @return scalar
	 */
	public function getOid() {
		if (!isset($this->properties['oid'])) {
			$this->properties['oid'] = self::generateOid();
		}
		
		return $this->properties['oid'];
	}
	
	/**
	 * @param salar $oid
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setOid($oid) {
		if (!is_null($oid) && !is_scalar($oid)) {
			throw new \InvalidArgumentException('Argument $oid must be scalar');
		}
		$this->properties['oid'] = $oid;
		
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getRnd() {
		if (!isset($this->properties['rnd'])) {
			$this->properties['rnd'] = self::generateRnd();
		}
		
		return $this->properties['rnd'];
	}
	
	/**
	 * @param salar $rnd
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setRnd($rnd) {
		if (!is_null($rnd) && !is_scalar($rnd)) {
			throw new \InvalidArgumentException('Argument $rnd must be scalar');
		}
		$this->properties['rnd'] = $rnd;
		
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getCurrency() {
		if (!isset($this->properties['currency'])) {
			$this->properties['currency'] = self::DEFAULT_CURRENCY;
		}
		
		return $this->properties['currency'];
	}
	
	/**
	 * @param salar $currency
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setCurrency($currency) {
		if (!is_null($currency) && !is_scalar($currency)) {
			throw new \InvalidArgumentException('Argument $currency must be scalar');
		}
		$this->properties['currency'] = $currency;
		
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getLang() {
		if (!isset($this->properties['lang'])) {
			$this->properties['lang'] = self::DEFAULT_LANG;
		}
		
		return $this->properties['lang'];
	}
	
	/**
	 * @param salar $lang
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setLang($lang) {
		if (!is_null($lang) && !is_scalar($lang)) {
			throw new \InvalidArgumentException('Argument $lang must be scalar');
		}
		$this->properties['lang'] = $lang;
		
		return $this;
	}
	
	/**
	 * @return float
	 */
	public function getAmount() {
		if (!isset($this->properties['amount'])) {
			$this->properties['amount'] = 0.01;
		}
		
		return $this->properties['amount'];
	}
	
	/**
	 * @param float $amount
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setAmount($amount) {
		if (!is_null($amount) && (!is_numeric($amount) || $amount <= 0)) {
			throw new \InvalidArgumentException('Argument $amount must be numeric greater than zero');
		}
		
		$this->properties['amount'] = floatval($amount);
		
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getTrantype() {
		if (!isset($this->properties['trantype'])) {
			$this->properties['trantype'] = self::TRAN_TYPE_AUTH;
		}
		
		return $this->properties['trantype'];
	}
	
	/**
	 * @param string $Trantype
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setTrantype($Trantype) {
		if (!is_null($Trantype) && $Trantype != self::TRAN_TYPE_AUTH && $Trantype != self::TRAN_TYPE_PREAUTH) {
			throw new \InvalidArgumentException('Argument $Trantype must be one of values: ' . self::TRAN_TYPE_AUTH . ' ' . self::TRAN_TYPE_PREAUTH);
		}
		
		$this->properties['trantype'] = $Trantype;
		
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getInstalment() {
		if (!isset($this->properties['instalment'])) {
			$this->properties['instalment'] = '';
		}
		
		return $this->properties['instalment'];
	}
	
	/**
	 * @param scalar $instalment
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setInstalment($instalment) {
		if (!is_null($instalment) && !is_scalar($instalment)) {
			throw new \InvalidArgumentException('Argument $instalment must be scalar');
		}
		
		$this->properties['instalment'] = intval($instalment);
		
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getProcessed() {
		if (!isset($this->properties['processed'])) {
			$this->properties['processed'] = 0;
		}
		
		return $this->properties['processed'];
	}
	
	/**
	 * @param int $processed
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setProcessed($processed) {
		$this->properties['processed'] = $processed ? 1 : 0;
		
		return $this;
	}
	
	/**
	 * @return boolean
	 */
	public function isProcessed() {
		return $this->getProcessed() ? true : false;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function isSuccess() {
		$response = $this->getProperty('Response');
		$procReturnCode = $this->getProperty('ProcReturnCode');
		$mdStatus = $this->getProperty('mdStatus');
		
		if (is_numeric($mdStatus) && !in_array(((int) $mdStatus), [1, 2, 3, 4])) {
			return false;
		}
		
		return $response == self::RESPONSE_APPROVED && $procReturnCode == self::PROC_RESPONSE_CODE_APPROVED;
	}
}


//		
