<?php

namespace Cubes\Nestpay;

class NestpayApi {
	private $lastRequestLog;
	private $lastHttpResponse;
	
	protected $userAgent = 'Cubes Nestpay PHP lib';
	protected $clientId;
	protected $apiEndpointUrl;
	protected $apiName;
	protected $apiPassword;
	
	public function __construct(array $properties = null) {
		if (is_array($properties)) {
			$this->setProperties($properties);
		}
	}
	
	/**
	 * @return scalar
	 */
	public function getClientId() {
		return $this->clientId;
	}
	
	/**
	 * @param scalar $clientId
	 * @return \Cubes\Nestpay\NestpayApi
	 * @throws \InvalidArgumentException
	 */
	public function setClientId($clientId) {
		if (!is_scalar($clientId)) {
			throw new \InvalidArgumentException('Argument $clientId must be scalar');
		}
		
		$this->clientId = $clientId;
		return $this;
	}

		
	/**
	 * @return scalar
	 */
	public function getApiEndpointUrl() {
		return $this->apiEndpointUrl;
	}
	
	/**
	 * @param scalar $apiEndpointUrl
	 * @return \Cubes\Nestpay\NestpayApi
	 * @throws \InvalidArgumentException
	 */
	public function setApiEndpointUrl($apiEndpointUrl) {
		if (!is_scalar($apiEndpointUrl)) {
			throw new \InvalidArgumentException('Argument $apiEndpointUrl must be scalar');
		}
		$this->apiEndpointUrl = $apiEndpointUrl;
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getApiName() {
		return $this->apiName;
	}
	
	/**
	 * @param scalar $apiName
	 * @return \Cubes\Nestpay\NestpayApi
	 * @throws \InvalidArgumentException
	 */
	public function setApiName($apiName) {
		if (!is_scalar($apiName)) {
			throw new \InvalidArgumentException('Argument $apiName must be scalar');
		}
		$this->apiName = $apiName;
		return $this;
	}
	
	/**
	 * @return scalar
	 */
	public function getApiPassword() {
		return $this->apiPassword;
	}
	
	/**
	 * @param scalar $apiPassword
	 * @return \Cubes\Nestpay\NestpayApi
	 * @throws \InvalidArgumentException
	 */
	public function setApiPassword($apiPassword) {
		if (!is_scalar($apiPassword)) {
			throw new \InvalidArgumentException('Argument $apiPassword must be scalar');
		}
		$this->apiPassword = $apiPassword;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getUserAgent() {
		return $this->userAgent;
	}
	
	/**
	 * @param scalar $userAgent
	 * @return \Cubes\Nestpay\NestpayApi
	 * @throws \InvalidArgumentException
	 */
	public function setUserAgent($userAgent) {
		if (!is_scalar($userAgent)) {
			throw new \InvalidArgumentException('Argument $userAgent must be scalar');
		}
		$this->userAgent = $userAgent;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getLastHttpResponse() {
		return $this->lastHttpResponse;
	}

	/**
	 * @param string $lastHttpResponse
	 * @return \Cubes\Nestpay\NestpayApi
	 */
	private function setLastHttpResponse($lastHttpResponse) {
		$this->lastHttpResponse = $lastHttpResponse;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getLastRequestLog() {
		return $this->lastRequestLog;
	}
	
	/**
	 * @param string $lastRequestLog
	 * @return \Cubes\Nestpay\NestpayApi
	 */
	private function setLastRequestLog($lastRequestLog) {
		$this->lastRequestLog = $lastRequestLog;
		return $this;
	}

	
	/**
	 * @param array $properties
	 * @return \Cubes\Nestpay\NestpayApi
	 */
	public function setProperties(array $properties) {
		foreach ($properties as $key => $value) {
			$setterMethod = 'set' . ucfirst($key);
			if (method_exists($this, $setterMethod)) {
				$this->$setterMethod($value);
			}
		}
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getProperties() {
		return [
			'apiEndpointUrl' => $this->getApiEndpointUrl(),
			'apiName' => $this->getApiName(),
			'apiPassword' => $this->getApiPassword(),
			'userAgent' => $this->getUserAgent(),
		];
	}
	
	protected function sendXmlRequest($xml) {
		$url = $this->getApiEndpointUrl();
		
		$curl = curl_init($url);
		if ($curl === false) {
			throw new \RuntimeException('Unable to initiate curl connection to url "' . $url . '"');
		}
		
		$verbose = fopen('php://temp', 'w+');
		if ($verbose) {
			curl_setopt($curl, CURLOPT_VERBOSE, true);
			curl_setopt($curl, CURLOPT_STDERR, $verbose);
		}
		
        //curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->getUserAgent());
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
			'DATA' => $xml
		)));
		
        $response = curl_exec($curl);
		
		if ($verbose) {
			rewind($verbose);
			$requestLog = stream_get_contents($verbose);
			fclose($verbose);
			
			$this->setLastRequestLog($requestLog);
		}
		
		if ($response === false) {
			$exceptionMessage = 'Unable to execute curl handler on url "' . $url . '" got error: [' . curl_errno($curl) . '] ' . curl_error($curl);
			curl_close($curl);
			throw new NestpayApiException($this->getLastRequestLog(), $exceptionMessage);
		}
		
		$this->setLastHttpResponse($response);
		
		$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($responseCode < 200 || $responseCode >= 300) {
			throw new NestpayApiException($this->getLastRequestLog(), 'Got invalid HTTP response code: ' . $responseCode);
		}
		
		$headesSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$headers = substr($response, 0, $headesSize);
		$body = substr($response, $headesSize);
        curl_close($curl);
		
		if (preg_match('/content-encoding:\s*gzip/si', $headers)) {
			$body = gzdecode($body);
		}
		
		$xmlResponse = simplexml_load_string($body);
		
		return $xmlResponse;
	}
	
	public function getPaymentData($oid) {
		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
				<CC5Request>
					<Name>' . htmlspecialchars($this->getApiName(), ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</Name>
					<Password>' . htmlspecialchars($this->getApiPassword(), ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</Password>
					<ClientId>' . htmlspecialchars($this->getClientId(), ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</ClientId>
					<OrderId>' . htmlspecialchars($oid, ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</OrderId>
					<Extra>
						<ORDERSTATUS>QUERY</ORDERSTATUS>
					</Extra>
				</CC5Request>';
		
		
		$responseDataRaw = $this->sendXmlRequest($xml);
		$responseParams = [];
		
		foreach ($responseDataRaw as $l1Key => $l1Val) {
			if ($l1Key == 'Extra') {
				foreach ($l1Val as $l2Key => $l2Val) {
					if ($l2Key == 'ORDERSTATUS') {
						foreach ($l2Val as  $l3Key => $l3Val) {
							$responseParams[strtolower($l3Key)] = (string) $l3Val;
						}
					} else {
						$responseParams[strtolower($l2Key)] = (string) $l2Val;
					}
				}
			} else {
				$responseParams[strtolower($l1Key)] = (string) $l1Val;
			}
		}
		
		if (!isset($responseParams['ord_id']) || $responseParams['ord_id'] !== $oid) {
			return null;
		}
		
		$paymentData = [
			'oid' => $oid
		];
		
		if (isset($responseParams['response'])) {
			$paymentData[Payment::PROP_RESPONSE] = $responseParams['response'];
		}
		
		if (isset($responseParams['proc_ret_cd'])) {
			$paymentData[Payment::PROP_PROCRETURNCODE] = $responseParams['proc_ret_cd'];
		}
		
		if (isset($responseParams['mdstatus'])) {
			$paymentData[Payment::PROP_MDSTATUS] = $responseParams['mdstatus'];
		}
		
		if (isset($responseParams['auth_code'])) {
			$paymentData[Payment::PROP_AUTHCODE] = $responseParams['auth_code'];
		}
		
		if (isset($responseParams['trans_id'])) {
			$paymentData[Payment::PROP_TRANSID] = $responseParams['trans_id'];
		}
		
		if (isset($responseParams['capture_amt'])) {
			$paymentData[Payment::PROP_AMOUNT] = $responseParams['capture_amt'] / 100;
		}
		
		$paymentData['processed'] = 0;
		
		if (isset($responseParams['trans_stat']) && in_array(strtolower($responseParams['trans_stat']), ['c', 'd', 'a'])) {
			
			$paymentData['processed'] = 1;
		}
		
		return $paymentData;
	}
}

