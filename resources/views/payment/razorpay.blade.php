@extends('template')
@section('main')
<div class="container-fluid container-fluid-90 margin-top-85 min-height">
	<div class="row">
	
                    <div class="col-md-8">
					<div id="loader" class="display-off single-load">
                         <img src="{{URL::to('/')}}/public/front/img/green-loader.gif" alt="loader">
                    </div>
                        @if($message = Session::get('error'))
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Error!</strong> {{ $message }}
                            </div>
                        @endif
                            <div class="alert alert-success success-alert alert-dismissible fade show" role="alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Success!</strong> <span class="success-message"></span>
                            </div>
                        {{ Session::forget('success') }}
                          
                                
                          <button id="rzp-button1" class="" style="border:0;"></button>
                           
                    </div>
					<div class="col-md-4 mb-5">
			<div class="card p-3">
				<a href="{{ url('/') }}/properties/{{ $result->id}}/{{$result->slug}}">
					<img class="card-img-top p-2 rounded" src="{{$result->cover_photo}}" alt="{{$result->name}}" height="180px">
				</a>
				<div class="card-body p-2">
					<a href="{{ url('/') }}/properties/{{ $result->id}}/{{$result->slug}}"><p class="text-16 font-weight-700 mb-0">{{ $result->name }}</p></a>
					
					<p class="text-14 mt-2 text-muted mb-0">
						<i class="fas fa-map-marker-alt"></i>
						{{$result->property_address->address_line_1}}, {{ $result->property_address->state }}, {{ $result->property_address->country_name }}
					</p>
					<div class="border p-4 mt-4 text-center">
						<p class="text-16 mb-0">
							<strong class="font-weight-700 secondary-text-color">{{ $result->property_type_name }}</strong> 
							{{trans('messages.payment.for')}}
							<strong class="font-weight-700 secondary-text-color">{{ $number_of_guests }} {{trans('messages.payment.guest')}}</strong> 
						</p>
						<div class="text-14"><strong>{{ date('D, M d, Y', strtotime($checkin)) }}</strong> to <strong>{{ date('D, M d, Y', strtotime($checkout)) }}</strong></div>					
					</div>

					<div class="border p-4 mt-3">

						@foreach( $price_list->date_with_price as $date_price)
						<div class="d-flex justify-content-between text-16">
							<div>
								<p class="pl-4">{{ $date_price->date }}</p>
							</div>
							<div>
								<p class="pr-4">{!! $date_price->price !!}</p>
							</div>
						</div>
						@endforeach
						<hr>
						<div class="d-flex justify-content-between text-16">
							<div>
								<p class="pl-4">{{trans('messages.payment.night')}}</p>
							</div>
							<div>
								<p class="pr-4">{{ $nights }}</p>
							</div>
						</div>
						
						<div class="d-flex justify-content-between text-16">
							<div>
								<p class="pl-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->property_price ) !!} x {{ $nights }} {{trans('messages.payment.nights')}}</p>
							</div>
							<div>
								<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->total_night_price ) !!}</p>
							</div>
						</div>
					
						@if($price_list->service_fee)
							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{trans('messages.payment.service_fee')}}</p>
								</div>

								<div>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->service_fee ) !!}</p>
								</div>
							</div>
						@endif
						
						@if($price_list->additional_guest)
							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{trans('messages.payment.additional_guest_fee')}}</p>
								</div>

								<div>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->additional_guest )!!}</p>
								</div>
							</div>
						@endif
					
						@if($price_list->security_fee)
							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{trans('messages.payment.security_deposit')}}</p>
								</div>

								<div>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol,  $price_list->security_fee )!!}</p>
								</div>
							</div>
						@endif
						
						@if($price_list->cleaning_fee)
							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{trans('messages.payment.cleaning_fee')}}</p>
								</div>

								<div>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->cleaning_fee )!!}</p>
								</div>
							</div>
						@endif

						@if($price_list->iva_tax)
							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{trans('messages.property_single.iva_tax')}}</p>
								</div>

								<div>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->iva_tax )!!}</p>
								</div>
							</div>
						@endif

						@if($price_list->accomodation_tax)
							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{trans('messages.property_single.accommodatiton_tax')}}</p>
								</div>

								<div>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->accomodation_tax )!!}</p>
								</div>
							</div>
						@endif
						<hr>
					
						<div class="d-flex justify-content-between font-weight-700 text-16">
							<div>
								<p class="pl-4">{{trans('messages.payment.total')}}</p>
							</div>

							<div>
								<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->total )!!}</p>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body text-16">
					<p class="exfont">
						{{trans('messages.payment.paying_in')}}
						<strong><span id="payment-currency">{!!moneyFormat($currencyDefault->org_symbol,$currencyDefault->code)!!}</span></strong>.
						{{trans('messages.payment.your_total_charge')}}
						<strong><span id="payment-total-charge">{!! moneyFormat($currencyDefault->org_symbol, $price_eur) !!}</span></strong>.
						{{trans('messages.payment.exchange_rate_booking')}} {!! $currencyDefault->org_symbol !!} 1 to {!! moneyFormat($result->property_price->currency->org_symbol, $price_rate ) !!} {!! $result->property_price->currency_code !!} ( {{trans('messages.listing_book.host_currency')}} ).
					</p>
				</div>
			</div>
			
		
	</div>
					 
          
       
    
</div>
</div>

@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
		$(document).ready(function(){
			$("#rzp-button1").trigger("click");
		});
	
        $('body').on('click','#rzp-button1',function(e){
            e.preventDefault();
            var amount = "{{ $price_list->total }}";
            var total_amount = amount * 100;
            var options = {
                "key": "{{ $razorpay_key }}", // Enter the Key ID generated from the Dashboard
                "amount": total_amount, // Amount is in currency subunits. Default currency is INR. Hence, 10 refers to 1000 paise
                "currency": "INR",
                "name": "{{ $site_name }}",
                "description": "",
                "image": "{{ $logo }}",
                "order_id": "{{ $booking_id }}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                "handler": function (response){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type:'GET',
                        url:"{{ route('payment') }}",
                        data:{razorpay_payment_id:response.razorpay_payment_id,amount:amount},
						 beforeSend: function(){
							$('#loader').show();
						},
                        success:function(data){
                            $('.success-message').text(data.success);
                            $('.success-alert').fadeIn('slow', function(){
                               $('.success-alert').delay(5000).fadeOut(); 
                            });
							$('#loader').hide();
							window.location.href = "{{url('/trips/active')}}";
                        }
                    });
                },
                "prefill": {
                    "name": "test",
                    "email": "test@example.com",
                    "contact": "818********6"
                },
                "notes": {
                    "address": "test test"
                },
                "theme": {
                    "color": "#F37254"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        });
    </script>
@endpush 
