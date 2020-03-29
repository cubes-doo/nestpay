<?php

namespace Cubes\Nestpay;

class PaymentStandard implements Payment
{
    use PaymentArrayAccessTrait;
    use PaymentTrait;

	//Payment methods
	public function __construct(array $properties = null) {
        $paymentProperties = [
            'processed' => 0,
            'oid' => self::generateOid(),
            'rnd' => self::generateRnd(),
            'currency' => self::DEFAULT_CURRENCY,
            'lang' => self::DEFAULT_LANG,
            'amount' => 0.01,
            'trantype' => self::TRAN_TYPE_AUTH,
        ];

		if (is_array($properties)) {
            $paymentProperties = array_merge($paymentProperties, $properties);
        }
        
        $this->setProperties($paymentProperties);
	}
}