<?php

class PaymentDao extends \PDO implements \Cubes\Nestpay\PaymentDao {
	
	/**
	 * @param scalar $oid
	 * @return \Cubes\Nestpay\Payment
	 */
	public function getPayment($oid) {
		$statement = $this->prepare('SELECT * FROM `nestpay_payment` WHERE `oid` = :oid ');
		$statement->execute([
			'oid' => $oid
		]);
		
		$properties = $statement->fetch(\PDO::FETCH_ASSOC);
		if (empty($properties)) {
			return null;
		}
		
		return new \Cubes\Nestpay\Payment($properties);
	}
	
	/**
	 * 
	 * @param \Cubes\Nestpay\Payment $payment
	 * @return \Cubes\Nestpay\Payment
	 */
	public function savePayment(\Cubes\Nestpay\Payment $payment) {
		$existingPayment = $this->getPayment($payment->getOid());
		
		$sql = '';
		if (!$existingPayment) {
			$properties = $payment->getProperties();
			
			$escapedValues = [];
			
			foreach ($properties as $key => $value) {
				$escapedValues[$key] = $this->quote($value);
			}
			
			$sql = 'INSERT INTO `nestpay_payment` (`' . implode('`, `', array_keys($escapedValues)) . '`) VALUES (' . implode(', ', $escapedValues) . ')';
			
		} else {
			$properties = array_merge($existingPayment->getProperties(), $payment->getProperties());
			
			$sql = 'UPDATE `nestpay_payment` SET ';
			
			$updateStatements = [];
			
			foreach ($properties as $key => $value) {
				$updateStatements[] = '`' . $key . '` = ' . $this->quote($value);
			}
			
			$sql .= implode(', ', $updateStatements);
			
			$sql .= ' WHERE `oid`= ' . $this->quote($payment->getOid());
			
			$payment->setProperties($properties);
		}
		
		$statement = $this->prepare($sql);
		
		$statement->execute();
		
		return $payment;
	}
}

