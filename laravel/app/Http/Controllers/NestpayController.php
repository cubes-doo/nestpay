<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Cubes\Nestpay\MerchantService;

class NestpayController extends Controller
{
	public function confirment(Request $request)
	{
        //this is start route where payment is confirmed
        //the form is pouplated by ajax from confirm action

        $paymentData = $this->getPaymentData();
        
        return view('nestpay::confirm', [
			'paymentData' => $paymentData
		]);
    }
    
    /**
     * This is ajax route
     * When payment is confirmed on previous page
     * the form parameters are generated and returned as json
     * then the form is populated and submited (POST-ed) to the nestpay 3d secure page.
     * This is done to avoid unnecessary creation of OID (nestpay_payments table records)
     *
     * @param MerchantService $nestpayMerchantService
     * @param Request $request
     * @return json
     */
    public function confirm(MerchantService $nestpayMerchantService, Request $request)
    {
        $paymentData = $this->getPaymentData();

        //change the routes for success and fail
        $nestpayMerchantService->getMerchantConfig()->setConfig([
			'okUrl' => route('nestpay.success'),
			'failUrl' => route('nestpay.fail'),
		]);
		
        $formFields = $nestpayMerchantService->paymentMakeRequestParameters($paymentData);
        
        /**
         * The working payment is created at this point
         * Set specific columns for nestpay model, like user_id , order_id etc
         */
        //$nestpayPayment = $nestpayMerchantService->getWorkingPayment();
        //$nestpayPayment->fill([
        //    'user_id' => auth()->user()->getAuthIdentifier(),
        //]);
        //$nestpayPayment->save();

        return response()->json($formFields);
    }

    /**
     * This is successfull processing
     * 
     *
     * @param MerchantService $nestpayMerchantService
     * @param Request $request
     * @return view
     */
    public function success(MerchantService $nestpayMerchantService, Request $request)
    {
        $payment = null;
        $ex = null;
		
		try {
            $payment = $nestpayMerchantService->paymentProcess3DGateResponse($request->all());
            
            //the payment has been process successfully 
            //THAT DOES NOT MEAN THAT CUSTOMER HAS PAID!!!!
            //FOR SUCCESSFULL PAYMENT SEE \App\Listeners\NestpayEventSubscriber!!!
            //DO NOT ADD CODE HERE FOR SUCCESSFULL PAYMENT!!!!
            
		} catch (\Cubes\Nestpay\PaymentAlreadyProcessedException $ex) {
            //the payment has been already processed
            //this error occures if customer refresh result page
            //add code here for the case if necessary 
            $ex = null;//comment this if you want to show this exception if debug is on

		} catch (\Exception $ex) {
			//any other error
            //add code here for the case if necessary 
		} finally {
            //try to get working payment

            try {
				$payment = $nestpayMerchantService->getWorkingPayment();
			} catch (\Exception $exTemp) {}
        }

        if ($ex && config('app.debug')) {
            //if debug is enabled throw exception
            throw $ex;
        }

        return view('nestpay::result', [
            'payment' => $payment,
            'exception' => $ex,
        ]);
    }

    /**
     * The fiail url
     * Process payment even in this case!!!
     *
     * @param MerchantService $nestpayMerchantService
     * @param Request $request
     * @return void
     */
    public function fail(MerchantService $nestpayMerchantService, Request $request)
    {
        $payment = null;
        $ex = null;
		
		try {
			$payment = $nestpayMerchantService->paymentProcess3DGateResponse($request->all());
            
		} catch (\Cubes\Nestpay\PaymentAlreadyProcessedException $ex) {
            //the payment has been already processed
            //this error occures if customer refresh result page
            //add code here for the case if necessary 
            $ex = null;//comment this if you want to show this exception if debug is on

		} catch (\Exception $ex) {
			//any other error
            //add code here for the case if necessary 

		} finally {
            //try to get working payment

            try {
				$payment = $nestpayMerchantService->getWorkingPayment();
			} catch (\Exception $exTemp) {}
        }

        if ($ex && config('app.debug')) {
            //if debug is enabled throw exception
            throw $ex;
        }

        return view('nestpay::result', [
            'payment' => $payment,
            'exception' => $ex,
            'isFail' => true
        ]);
    }

    /**
     * This is just a helper function forCinitial example to work
     * Change the details in production
     *
     * @return void
     */
    protected function getPaymentData()
    {
        return [
            \Cubes\Nestpay\Payment::PROP_TRANTYPE => \Cubes\Nestpay\Payment::TRAN_TYPE_PREAUTH, //two step processing
            //\Cubes\Nestpay\Payment::PROP_TRANTYPE => \Cubes\Nestpay\Payment::TRAN_TYPE_AUTH, //single step processing

            //Below is required data for payment

            \Cubes\Nestpay\Payment::PROP_AMOUNT => 25.64,
            \Cubes\Nestpay\Payment::PROP_CURRENCY => \Cubes\Nestpay\Payment::CURRENCY_RSD,
            //change with the name of your customer (reading from config is just for example)
            \Cubes\Nestpay\Payment::PROP_BILLTONAME => config('mail.from.name', 'FirstName LastName'),
            //change with email of your customer (reading from config is just for example)
			\Cubes\Nestpay\Payment::PROP_EMAIL => config('mail.from.address', 'mailbox@example.com'),
            
            

            //Below is optional data for payment (here are added for example)
            
            //This is good practice to read language from app locale
			\Cubes\Nestpay\Payment::PROP_LANG => app()->getLocale(),

			\Cubes\Nestpay\Payment::PROP_INVOICENUMBER => '144566789', //MUST BE NUMERIC!!!
			\Cubes\Nestpay\Payment::PROP_DESCRIPTION => 'Order on my website',
			
			\Cubes\Nestpay\Payment::PROP_BILLTOSTREET1 => 'My Street',
			\Cubes\Nestpay\Payment::PROP_BILLTOCITY => 'My City',
			\Cubes\Nestpay\Payment::PROP_BILLTOCOUNTRY => 'My Country',
			\Cubes\Nestpay\Payment::PROP_TEL => '1 55 555 555',
        ];
    }
}
