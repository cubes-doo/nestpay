<?php

namespace Cubes\Nestpay\Laravel;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Cubes\Nestpay\Payment;

class NestpayPaymentProcessedSuccessfullyEvent
{
    use Dispatchable, SerializesModels;

    protected $payment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return \Cubes\Nestpay\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
