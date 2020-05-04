<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Cubes\Nestpay\Laravel\NestpayPaymentProcessedSuccessfullyEvent;
use Cubes\Nestpay\Laravel\NestpayPaymentProcessedFailedEvent;
use Cubes\Nestpay\Laravel\NestpayPaymentProcessedErrorEvent;

use Cubes\Nestpay\Payment;

use App\Mail\NestpayPaymentMail;

class NestpayEventsSubscriber
{
    /**
     * Successfull payment
     */
    public function nestpayPaymentProcessedSuccessfullyEvent(NestpayPaymentProcessedSuccessfullyEvent $event) {
        $payment = $event->getPayment();

        //CUSTOMER HAS PAID, DO RELATED STUFF HERE

        //sending email
        \Mail::to(
            $payment->getProperty(Payment::PROP_EMAIL),
            $payment->getProperty(Payment::PROP_BILLTONAME)
        )->send(new NestpayPaymentMail($payment));
    }

    /**
     * Failed payment
     */
    public function nestpayPaymentProcessedFailedEvent(NestpayPaymentProcessedFailedEvent $event) {
        $payment = $event->getPayment();


        //sending email
        \Mail::to(
            $payment->getProperty(Payment::PROP_EMAIL),
            $payment->getProperty(Payment::PROP_BILLTONAME)
        )->send(new NestpayPaymentMail($payment));
    }

    /**
     * Error processing payment
     */
    public function nestpayPaymentProcessedErrorEvent(NestpayPaymentProcessedErrorEvent $event) {
        $payment = $event->getPayment(); //COULD BE NULL!!!
        $ex = $event->getException();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Cubes\Nestpay\Laravel\NestpayPaymentProcessedSuccessfullyEvent',
            'App\Listeners\NestpayEventsSubscriber@nestpayPaymentProcessedSuccessfullyEvent'
        );

        $events->listen(
            'Cubes\Nestpay\Laravel\NestpayPaymentProcessedFailedEvent',
            'App\Listeners\NestpayEventsSubscriber@nestpayPaymentProcessedFailedEvent'
        );

        $events->listen(
            'Cubes\Nestpay\Laravel\NestpayPaymentProcessedErrorEvent',
            'App\Listeners\NestpayEventsSubscriber@nestpayPaymentProcessedErrorEvent'
        );
    }
}
