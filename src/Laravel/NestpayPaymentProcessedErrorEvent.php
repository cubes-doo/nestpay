<?php

namespace Cubes\Nestpay\Laravel;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Cubes\Nestpay\Payment;

class NestpayPaymentProcessedErrorEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var \Cubes\Nestpay\Payment
     */
    protected $payment;

    /**
     * @var \Throwable
     */
    protected $exception;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Payment $payment = null, \Throwable $exception = null)
    {
        $this->payment = $payment;

        $this->exception = $exception;
    }

    /**
     * @return \Cubes\Nestpay\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return \Throwable
     */
    public function getException()
    {
        return $this->exception;
    }
}
