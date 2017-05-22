<?php

namespace Cubes\Nestpay;

interface PaymentDao {
	
	/**
	 * Fetch payment by $oid
	 * 
	 * @return \Cubes\Nestpay\Payment
	 * @param scalar $oid
	 */
	public function getPayment($oid);
	
	/**
	 * Saves the payment
	 * 
	 * @param \Cubes\Nestpay\Payment $payment
	 * @return \Cubes\Nestpay\Payment
	 */
	public function savePayment(Payment $payment);
}
