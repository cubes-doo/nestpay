@inject('nestpayMerchantService', 'nestpay')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('Confirm Payment')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    
    <main class="container">
        <div class="row">
            <div class="col-lg-12">
                <form 
                    class="card"
                    action="{{$nestpayMerchantService->get3DGateUrl()}}"
                    method="post"
                    id="nestpay-confirm-payment-form"
                >
                    <div class="card-header">@lang('Confirm Payment')</div>
                    <div class="card-body">
                        <h1>@lang('Please review and confirm your payment')</h1>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>Your Name:</th>
                                    <td>{{$paymentData['BillToName']}}</td>
                                </tr>
                                <tr>
                                    <th>Your Email:</th>
                                    <td>{{$paymentData['email']}}</td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td>
                                        {{$paymentData['amount']}}
                                        RSD
                                        ({{$paymentData['currency']}})
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button id="nestpay-confirm-payment-submit" type="submit" class="btn btn-success">Confirm</button>
                        <div 
                            id="nestpay-confirm-payment-errors"
                            class="text-danger"
                        ></div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
    $('#nestpay-confirm-payment-form').on('submit', function(e) {
		e.preventDefault();
        e.stopPropagation();
        
        let nestpayConfirmPaymentForm = $(this);

        $('#nestpay-confirm-payment-submit').attr('disabled', 'disabled');
		
		$.ajax({
			'url': "{{route('nestpay.confirm')}}",
			'type': "post",
			'data': {
				'_token': "{{csrf_token()}}"
			}
		}).done(function(data) {
			
			for (var field in data) {
				var value = data[field];
				
				var inputField = $('<input type="hidden">');
				inputField.attr('name', field);
				inputField.attr('value', value);
				nestpayConfirmPaymentForm.prepend(inputField);
			}
				
			nestpayConfirmPaymentForm.get(0).submit();
		}).fail(function() {
			$('#nestpay-confirm-payment-errors').html("{{__('An error occured while confirming payment')}}");
		}).always(function () {
            $('#nestpay-confirm-payment-submit').removeAttr('disabled');
        });
	});
    </script>
</body>
</html>