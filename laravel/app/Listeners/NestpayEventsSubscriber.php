<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Cubes\Nestpay\Laravel\NestpayPaymentProcessedSuccessfullyEvent;
use Cubes\Nestpay\Laravel\NestpayPaymentProcessedFailedEvent;
use Cubes\Nestpay\Laravel\NestpayPaymentProcessedErrorEvent;

class NestpayEventsSubscriber
{
    /**
     * Successfull payment
     */
    public function nestpayPaymentProcessedSuccessfully(NestpayPaymentProcessedSuccessfullyEvent $event) {
        
    }

    /**
     * Failed payment
     */
    public function nestpayPaymentProcessedFailedEvent(NestpayPaymentProcessedFailedEvent $event) {

    }

    /**
     * Error processing payment
     */
    public function nestpayPaymentProcessedErrorEvent(NestpayPaymentProcessedErrorEvent $event) {

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
