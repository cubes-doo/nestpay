<?php

namespace Cubes\Nestpay;

trait PaymentArrayAccessTrait 
{
	/**
	 * @var array 
	 */
    protected $properties = [];

	
	
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
		
		if (!in_array($key, self::ALLOWED_PROPERTIES)) {
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
    
    protected function _setAttribute($key, $value)
    {
        $this->properties[$key] = $value;
    }

    protected function _getAttribute($key, $defaultValue = null)
    {
        if (!is_null($defaultValue) && !isset($this->properties[$key])) {
            $this->properties[$key] = $defaultValue;
        }

        return isset($this->properties[$key]) ? $this->properties[$key] : null;
    }
}
