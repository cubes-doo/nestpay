<?php

namespace Cubes\Nestpay\Laravel;

use Cubes\Nestpay\PaymentDao;
use Cubes\Nestpay\Payment;
use \Illuminate\Database\Eloquent\Model;

class PaymentDaoEloquent implements PaymentDao
{
    protected $paymentModel;

    public function __construct($paymentModel)
    {
        $this->setPaymentModel($paymentModel);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getPaymentModel()
    {
        return $this->paymentModel;
    }

    public function setPaymentModel($paymentModel)
    {
        if (is_string($paymentModel)) {
            $paymentModel = new $paymentModel();
        }

        if (!($paymentModel instanceof Model)) {
            throw new \InvalidArgumentException('Payment model must be eloquent model');
        }

        if (!($paymentModel instanceof Payment)) {
            throw new \InvalidArgumentException('Payment model must be instanceof Cubes\\Nestpay\\Payment');
        }

        $this->paymentModel = $paymentModel;

        return $this;
    }

    /**
	 * Fetch payment by $oid
	 * 
	 * @return \Cubes\Nestpay\Payment
	 * @param scalar $oid
	 */
    public function getPayment($oid)
    {
        return $this->getPaymentModel()->query()->where('oid', $oid)->first();
    }
	
	/**
	 * Saves the payment
	 * 
	 * @param \Cubes\Nestpay\Payment $payment
	 * @return \Cubes\Nestpay\Payment
	 */
    public function savePayment(Payment $payment)
    {
        if ($payment instanceof Model) {
            $payment->save();

            return $payment;
        }

        $existingPayment = $this->getPayment($payment->getOid());
        if (!$existingPayment) {
            return $this->createPayment($payment->getProperties());
        }

        $existingPayment->fill($payment->getProperties());

        $existingPayment->save();

        return $existingPayment;
    }

	/**
	 * Creates new payment
	 *
	 * @param array $properties
	 * @return \Cubes\Nestpay\Payment
	 */
    public function createPayment(array $properties)
    {
        $newPayment = clone $this->getPaymentModel();

        if (!isset($properties[Payment::PROP_OID])) {
            $properties[Payment::PROP_OID] = $newPayment::generateOid();
        }

        if (!isset($properties[Payment::PROP_TRANTYPE])) {
            $properties[Payment::PROP_TRANTYPE] = Payment::TRAN_TYPE_AUTH;
        }

        $newPayment->fill($properties);
        $newPayment->save();

        return $newPayment;
    }
}