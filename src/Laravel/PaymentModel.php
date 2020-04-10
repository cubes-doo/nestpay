<?php

namespace Cubes\Nestpay\Laravel;

use Illuminate\Database\Eloquent\Model;

use Cubes\Nestpay\Payment;
use Cubes\Nestpay\PaymentTrait;

class PaymentModel extends Model implements Payment
{
    use PaymentTrait;

    protected $table = 'nestpay_payments';
	
	protected $fillable = Payment::ALLOWED_PROPERTIES;
	
	/**
	 * 
	 * @param string $key
	 * @param scalar $value
	 * @return \Cubes\Nestpay\Payment
	 * @throws \InvalidArgumentException
	 */
	public function setProperty($key, $value) {
		
		if (!in_array($key, Payment::ALLOWED_PROPERTIES)) {
			return $this;
		}

        $this->setAttribute($key, $value);
		return $this;
	}
	
	public function getProperty($key) {
		return $this->getAttribute($key);
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
			$properties = $this->getAttributes();
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
    
    protected function _setAttribute($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    protected function _getAttribute($key, $defaultValue = null)
    {
        $attr = $this->getAttribute($key);

        if (is_null($attr) && !is_null($defaultValue)) {
            $this->setProperty($key, $defaultValue);

            $attr = $defaultValue;
        }

        return $attr;
    } 
}