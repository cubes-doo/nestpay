<?php

namespace Cubes\Nestpay\Laravel;

use App\Http\Controllers\NestpayController;
use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    protected static function getFacadeAccessor()
    {
        return 'nestpay';
    }

    public static function routes()
    {
        \Route::prefix('/nestpay')->group(function () {
            \Route::get('/confirm', NestpayController::class, 'confirment')->name('nestpay.confirment');
            \Route::post('/confirm', NestpayController::class, 'confirm')->name('nestpay.confirm');
            \Route::post('/success', NestpayController::class, 'success')->name('nestpay.success');
            \Route::post('/fail', NestpayController::class, 'fail')->name('nestpay.fail');
        });
    }
}