@php
    $successfulPayment = !empty($payment) && $payment->isSuccess();
@endphp
<!DOCTYPE html>
<html lang="sr">
	<head>
		<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <style>
			* {
				box-sizing: border-box;
				font-family: Helvetica, Arial, Verdana;
				font-size: 12px;
			}
		</style>
	</head>
	<body style="margin: 0px auto; width: 90%; max-width: 800px;">
		
		@if($successfulPayment)
        <h1 style="font-size: 16px; color: #28A44A">@lang('Your payment is successfull')</h1>
        @else
        <h1 style="font-size: 16px; color: #ff5c57">@lang('Your payment has failed')</h1>
        @endif
        <br>
        @if(!empty($payment))
		<table style="border-collapse: collapse; border-spacing: 0px; width: 100%; text-align: left;">
			<thead>
				<tr>
					<th style="padding: 10px; text-transform: uppercase; border-bottom: 3px solid #ddd; color: #333; padding: 10px; font-size: 16px;" colspan="2">
                        @lang('Payment details')
					</th>
				</tr>
            </thead>
            <tbody>
                @if(!empty($payment['BillToName']))
                <tr>
                    <td class="">@lang('Customer Name')</td>
                    <td>{{$payment['BillToName']}}</td>
                </tr>
                @endif
                @if(!empty($payment['email']))
                <tr>
                    <td class="">@lang('Customer Email')</td>
                    <td>{{$payment['email']}}</td>
                </tr>
                @endif
                @if(!empty($payment['amount']))
                <tr>
                    <td class="">@lang('Amount')</td>
                    <td>{{$payment['amount']}} RSD</td>
                </tr>
                @endif
                @if(!empty($payment['currency']))
                <tr>
                    <td class="">@lang('Currency')</td>
                    <td>{{$payment['currency']}}</td>
                </tr>
                @endif
                @if(!empty($payment['EXTRA_TRXDATE']))
                <tr>
                    <td style="text-align: left; width: 40%;">@lang('Time')</td>
                    <td class="text-uppercase">{{$payment['EXTRA_TRXDATE']}}</td>
                </tr>
                @endif
                @if(!empty($payment['oid']))
                <tr>
                    <td style="text-align: left; width: 40%;">@lang('OID')</td>
                    <td class="text-uppercase">{{$payment['oid']}}</td>
                </tr>
                @endif
                @if(!empty($payment['Response']))
                <tr>
                    <td style="text-align: left; width: 40%;">@lang('Response')</td>
                    <td class="text-uppercase">{{$payment['Response']}}</td>
                </tr>
                @endif
                @if(!empty($payment['AuthCode']))
                <tr>
                    <td style="text-align: left; width: 40%;">@lang('AuthCode')</td>
                    <td class="text-uppercase">{{$payment['AuthCode']}}</td>
                </tr>
                @endif
                @if(!empty($payment['TransId']))
                <tr>
                    <td style="text-align: left; width: 40%;">@lang('TransId')</td>
                    <td class="text-uppercase">{{$payment['TransId']}}</td>
                </tr>
                @endif
                @if(!empty($payment['ProcReturnCode']))
                <tr>
                    <td style="text-align: left; width: 40%;">@lang('ProcReturnCode')</td>
                    <td class="text-uppercase">{{$payment['ProcReturnCode']}}</td>
                </tr>
                @endif
                @if(!empty($payment['mdStatus']))
                <tr>
                    <td style="text-align: left; width: 40%;">@lang('mdStatus')</td>
                    <td class="text-uppercase">{{$payment['mdStatus']}}</td>
                </tr>
                @endif
            </tbody>
		</table>
        <br>
        @endif
		<div style="margin-top: 30px; background-color: #aaa; padding: 10px;">
			<p style="font-size: 16px;">Your organization</p>
			<p><small>Your street, Your City, Your Country</small></p>
			<p><small>0 55 555 555</small></p>
            <p><a href="{{config('app.url')}}">{{config('app.name')}}</a></p>
		</div>
	</body>
</html>