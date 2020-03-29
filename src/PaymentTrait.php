<?php

namespace Cubes\Nestpay;

trait PaymentTrait 
{
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
	 * @return scalar
	 */
	public function getOid() {

        return $this->_getAttribute('oid', self::generateOid());
	}
	
	/**
	 * @param salar $oid
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setOid($oid) {
		if (!is_null($oid) && !is_scalar($oid)) {
			throw new \InvalidArgumentException('Argument $oid must be scalar');
        }

		$this->_setAttribute('oid', $oid);
		
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getRnd() {

        return $this->_getAttribute('rnd', self::generateRnd());
	}
	
	/**
	 * @param salar $rnd
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setRnd($rnd) {
		if (!is_null($rnd) && !is_scalar($rnd)) {
			throw new \InvalidArgumentException('Argument $rnd must be scalar');
        }
        
		$this->_setAttribute('rnd', $rnd);
		
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getCurrency() {
        return $this->_getAttribute('currency', self::DEFAULT_CURRENCY);
	}
	
	/**
	 * @param salar $currency
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setCurrency($currency) {
		if (!is_null($currency) && !is_scalar($currency)) {
			throw new \InvalidArgumentException('Argument $currency must be scalar');
		}
		$this->_setAttribute('currency', $currency);
		
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getLang() {

        return $this->_getAttribute('lang', self::DEFAULT_LANG);
	}
	
	/**
	 * @param salar $lang
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setLang($lang) {
		if (!is_null($lang) && !is_scalar($lang)) {
			throw new \InvalidArgumentException('Argument $lang must be scalar');
        }
        
		$this->_setAttribute('lang', $lang);
		
		return $this;
	}
	
	/**
	 * @return float
	 */
	public function getAmount() {
        return $this->_getAttribute('amount', 0.01);
	}
	
	/**
	 * @param float $amount
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setAmount($amount) {
		if (!is_null($amount) && (!is_numeric($amount) || $amount <= 0)) {
			throw new \InvalidArgumentException('Argument $amount must be numeric greater than zero');
		}
		
        $this->_setAttribute('amount', floatval($amount));
        
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getTrantype() {
        return $this->_getAttribute('trantype', self::TRAN_TYPE_AUTH);
	}
	
	/**
	 * @param string $Trantype
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setTrantype($Trantype) {
		if (!is_null($Trantype) && $Trantype != self::TRAN_TYPE_AUTH && $Trantype != self::TRAN_TYPE_PREAUTH) {
			throw new \InvalidArgumentException('Argument $Trantype must be one of values: ' . self::TRAN_TYPE_AUTH . ' ' . self::TRAN_TYPE_PREAUTH);
		}
		
        $this->_setAttribute('trantype', $Trantype);
        
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getInstalment() {
        return $this->_getAttribute('instalment', '');
	}
	
	/**
	 * @param scalar $instalment
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setInstalment($instalment) {
		if (!is_null($instalment) && !is_scalar($instalment)) {
			throw new \InvalidArgumentException('Argument $instalment must be scalar');
		}
        
        $this->_setAttribute('instalment', intval($instalment));
		
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getProcessed() {
        return $this->_getAttribute('processed', 0);
	}
	
	/**
	 * @param int $processed
	 * @return \Cubes\Nestpay\Payment
	 */
	public function setProcessed($processed) {

        $this->_setAttribute('processed', $processed ? 1 : 0);
		
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
		$response = ucfirst(strtolower($this->getProperty('Response')));
		$procReturnCode = $this->getProperty('ProcReturnCode');
		$mdStatus = $this->getProperty('mdStatus');
		
		if (is_numeric($mdStatus) && !in_array(((int) $mdStatus), [1, 2, 3, 4, 7])) {
			return false;
		}
		
		return $response == self::RESPONSE_APPROVED && $procReturnCode == self::PROC_RESPONSE_CODE_APPROVED;
	}
}
