<?php

namespace Cubes\Nestpay;


class MerchantConfig implements \ArrayAccess, \JsonSerializable {
	
	protected $config = [
		'clientId' => '',
		'storeKey' => '',
		'storeType' => '3D_PAY_HOSTING',
		'okUrl' => '',
		'failUrl' => '',
		'3DGateUrl' => '',
		
		//API
		'apiName' => '',
		'apiPassword' => '',
		'apiEndpointUrl' => ''
	];
	
	function __construct(array $config = null) {
		if (!is_null($config)) {
			$this->setConfig($config);
		}
	}

	
	//put your code here
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->config);
	}

	public function offsetGet($offset) {
		return isset($this->config[$offset]) ? $this->config[$offset] : null;
	}

	public function offsetSet($offset, $value) {
		if (!is_scalar($value) || is_null($value)) {
			throw new \InvalidArgumentException('Argument $value must be scalar got ' . (is_array($value) ? 'array' : (is_null($value) ? 'null' : get_class($value))));
		}
		
		$this->config[$offset] = $value;
	}

	public function offsetUnset($offset) {
		if (isset($this->config[$offset])) {
			unset($this->config[$offset]);
		}
	}
	
	/**
	 * @param array $config
	 * @return \Cubes\Nestpay\MerchantConfig
	 * @throws \InvalidArgumentException
	 */
	public function setConfig(array $config) {
		foreach ($config as $key => $val) {
			if (!is_string($key) || empty($key)) {
				throw new \InvalidArgumentException('Keys of array $config must be non empty strings got: ' . $key);
			}
			
			$this->offsetSet($key, $val);
		}
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function toArray() {
		return $this->config;
	}
	
	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return $this->toArray();
	}
	
	/**
	 * @return scalar
	 */
	public function getClientId() {
		return $this->offsetGet('clientId');
	}
	
	/**
	 * @return scalar
	 */
	public function getStoreKey() {
		return $this->offsetGet('storeKey');
	}
	
	/**
	 * @return scalar
	 */
	public function getStoreType() {
		return $this->offsetGet('storeType');
	}
	
	/**
	 * @return scalar
	 */
	public function getOkUrl() {
		return $this->offsetGet('okUrl');
	}
	
	/**
	 * @return scalar
	 */
	public function getFailUrl() {
		return $this->offsetGet('failUrl');
	}
	
	/**
	 * @return scalar
	 */
	public function get3DGateUrl() {
		return $this->offsetGet('3DGateUrl');
	}
	
	/**
	 * @return scalar
	 */
	public function getApiName() {
		return $this->offsetGet('apiName');
	}
	
	/**
	 * @return scalar
	 */
	public function getApiPassword() {
		return $this->offsetGet('apiPassword');
	}
	
	/**
	 * @return scalar
	 */
	public function getApiEndpointUrl() {
		return $this->offsetGet('apiEndpointUrl');
	}
}
