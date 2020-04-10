@php
$successfulPayment = !empty($payment) && $payment->isSuccess();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Payment Result')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    
    <main class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card {{$successfulPayment ? 'card-success' : 'card-danger'}}">
                    <div class="card-header">@lang('Confirm Payment')</div>
                    <div class="card-body">
                        @if($successfulPayment)
                        <h1 class="text-success">@lang('Your payment is successfull')</h1>
                        @else
                        <h1 class="text-danger">@lang('Your payment has failed')</h1>
                        @endif
                        @if(!empty($payment))
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase" colspan="2">
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
                                    <td class="">@lang('Time')</td>
                                    <td class="text-uppercase">{{$payment['EXTRA_TRXDATE']}}</td>
                                </tr>
                                @endif
                                @if(!empty($payment['oid']))
                                <tr>
                                    <td class="">@lang('OID')</td>
                                    <td class="text-uppercase">{{$payment['oid']}}</td>
                                </tr>
                                @endif
                                @if(!empty($payment['Response']))
                                <tr>
                                    <td class="">@lang('Response')</td>
                                    <td class="text-uppercase">{{$payment['Response']}}</td>
                                </tr>
                                @endif
                                @if(!empty($payment['ErrMsg']) && config('app.debug'))
                                <tr>
                                    <td class="">@lang('ErrMsg')</td>
                                    <td class="">{{$payment['ErrMsg']}}</td>
                                </tr>
                                @endif
                                @if(!empty($payment['AuthCode']))
                                <tr>
                                    <td class="">@lang('AuthCode')</td>
                                    <td class="text-uppercase">{{$payment['AuthCode']}}</td>
                                </tr>
                                @endif
                                @if(!empty($payment['TransId']))
                                <tr>
                                    <td class="">@lang('TransId')</td>
                                    <td class="text-uppercase">{{$payment['TransId']}}</td>
                                </tr>
                                @endif
                                @if(!empty($payment['ProcReturnCode']))
                                <tr>
                                    <td class="">@lang('ProcReturnCode')</td>
                                    <td class="text-uppercase">{{$payment['ProcReturnCode']}}</td>
                                </tr>
                                @endif
                                @if(!empty($payment['mdStatus']))
                                <tr>
                                    <td class="">@lang('mdStatus')</td>
                                    <td class="text-uppercase">{{$payment['mdStatus']}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{route('nestpay.confirment')}}" class="btn btn-secondary">OK</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>