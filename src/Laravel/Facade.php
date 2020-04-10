<?php

namespace Cubes\Nestpay\Laravel;

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
            \Route::get('/confirm', 'NestpayController@confirment')->name('nestpay.confirment');
            \Route::post('/confirm', 'NestpayController@confirm')->name('nestpay.confirm');
            \Route::post('/success', 'NestpayController@success')->name('nestpay.success');
            \Route::post('/fail', 'NestpayController@fail')->name('nestpay.fail');
        });
    }
}