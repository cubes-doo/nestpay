<?php

namespace Cubes\Nestpay\Laravel;

use Illuminate\Console\Command;

use Cubes\Nestpay\MerchantService;

class NestpayHandleUnprocessedPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nestpay:handle-unprocessed-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle unprocessed payments over Nestpay API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(MerchantService $merchantService) {

        $nestpayPaymentModel = $merchantService->getPaymentDao()->getPaymentModel();

        $unprocessedNotBefore = new \Carbon\Carbon('-' . config('nestpay.unprocessed_payments_not_before') . ' seconds');
        
        if ($unprocessedNotBefore->gt(\Carbon\Carbon::now())) {
            throw new \InvalidArgumentException('Nestpay config nestpay.unprocessed_payments_not_before is invalid, must be integer in seconds');
        }
        
        $unprocessedTimeToLive = new \Carbon\Carbon('-' . config('nestpay.unprocessed_payments_time_to_live') . ' seconds');
        
        if ($unprocessedTimeToLive->gt(\Carbon\Carbon::now())) {
            throw new \InvalidArgumentException('Nestpay config nestpay.unprocessed_payments_time_to_live is invalid, must be integer in seconds');
        }

        $unprocessedApiCallTimeout = config('nestpay.unprocessed_payments_api_call_timeout');

        if (!is_int($unprocessedApiCallTimeout) || $unprocessedApiCallTimeout < 0) {
            throw new \InvalidArgumentException('Nestpay config nestpay.unprocessed_payments_api_call_timeout is invalid, must be integer in seconds');
        }

        $unprocessedPayments = $nestpayPaymentModel->where('processed', 0)
            ->where('created_at', '<', $unprocessedNotBefore->format('Y-m-d H:i:s'))
            ->get();
      
        foreach ($unprocessedPayments as $unprocessedPaymentModel) {
        
            if ($unprocessedPaymentModel->created_at->lt($unprocessedTimeToLive)) {
              
                $unprocessedPaymentModel->processed = 1;
                $unprocessedPaymentModel->save();
                continue;
            }
        
            $merchantService->paymentProcessOverNestpayApi($unprocessedPaymentModel->toNestpayPaymentObject());
            
            sleep($unprocessedApiCallTimeout);
        }
    }
}