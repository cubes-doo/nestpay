<?php

namespace Cubes\Nestpay;

class Nestpay3DGateResponseException extends NestpayException {
	
	protected $responseData;
	
	public function __construct($responseData = null, $message = "", $code = 0, \Throwable $previous = null) {
		$this->responseData = $responseData;
		
		parent::__construct($message, $code, $previous);
	}
	
	public function getResponseData() {
		return $this->responseData;
	}

	public function setResponseData($responseData) {
		$this->responseData = $responseData;
		return $this;
	}
}
