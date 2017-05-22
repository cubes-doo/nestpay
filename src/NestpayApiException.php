<?php

namespace Cubes\Nestpay;

class NestpayApiException extends NestpayException {
	
	protected $httpLog;
	
	public function __construct($httpLog = null, $message = "", $code = 0, \Throwable $previous = null) {
		$this->httpLog = $httpLog;
		
		parent::__construct($message, $code, $previous);
	}
	
	public function getHttpLog() {
		return $this->httpLog;
	}

	public function setHttpLog($httpLog) {
		$this->httpLog = $httpLog;
		return $this;
	}
}
