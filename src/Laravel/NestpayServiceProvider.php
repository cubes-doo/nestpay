<?php

namespace Cubes\Nestpay\Laravel;

use Illuminate\Support\ServiceProvider;
use Cubes\Nestpay\MerchantService;

class NestpayServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        //config
        $this->publishes([
            __DIR__ . '/../../laravel/config/nestpay.php' => config_path('nestpay.php'),
        ], 'config');

        //migrations
        $this->loadMigrationsFrom(__DIR__.'/../../laravel/database/migrations');

        $this->publishes([
            __DIR__ . '/../../laravel/database/migrations' => database_path('migrations'),
        ], 'migrations');

        //models
        $this->publishes([
            __DIR__ . '/../../laravel/app/Models' => app_path('Models'),
        ], 'models');

        //listeners
        $this->publishes([
            __DIR__ . '/../../laravel/app/Listeners' => app_path('Listeners'),
        ], 'listeners');
        
        //console
        if ($this->app->runningInConsole()) {
            $this->commands([
                NestpayHandleUnprocessedPaymentCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../laravel/config/nestpay.php', 'nestpay'
        );
        
        //Register MerchantService in laravel
        $this->app->singleton(MerchantService::class, function ($app) {
            $merchantConfig = config('nestpay.merchant');

            $paymentModelClass = config('nestpay.paymentModel');

            if (empty($paymentModelClass)) {
                throw new \InvalidArgumentException('Config nestpay.paymentModel must be name of the payment model class');
            }

            $paymentDao = new PaymentDaoEloquent($paymentModelClass);

            $merchantService = new MerchantService([
                'merchantConfig' => $merchantConfig,
                'paymentDao' => $paymentDao,
            ]);

            $merchantService->onFailedPayment(function ($payment) {
                //just trigger event
                event(new \Cubes\Nestpay\Laravel\NestpayPaymentProcessedFailedEvent($payment));
            })->onSuccessfulPayment(function($payment) {
                //just trigger event
                event(new \Cubes\Nestpay\Laravel\NestpayPaymentProcessedSuccessfullyEvent($payment));
            })->onError(function($payment, $ex) {
                //just trigger event
                event(new \Cubes\Nestpay\Laravel\NestpayPaymentProcessedErrorEvent($payment, $ex));

                if (config('nestpay.throwExceptions')) {
                    throw $ex;
                }
            });

            return $merchantService;
        });

        $this->app->alias(MerchantService::class, 'nestpay');
    }
}