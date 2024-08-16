@extends('template')

@section('main')
<div class="container-fluid container-fluid-90 margin-top-85 min-height">
	@if(Session::has('message'))
		<div class="row mt-5">
			<div class="col-md-12 text-13 alert mb-0 {{ Session::get('alert-class') }} alert-dismissable fade in  text-center opacity-1">
				<a href="#"  class="close " data-dismiss="alert" aria-label="close">&times;</a>
				{{ Session::get('message') }}
			</div>
		</div>
	@endif 
	

	<div class="row justify-content-center">
		<div class="col-md-8 mb-5 mt-3 main-panel p-5 border rounded">
			<form action="{{ url('payments/create_booking') }}" method="post" id="checkout-form">
				{{ csrf_field() }}
				<div class="row justify-content-center">
				<input name="property_id" type="hidden" value="{{ $property_id }}">
			
			    <input name="family_price" type="hidden" value="@if(isset($family_price)){{ $family_price }}@endif">
			
				<input name="checkin" type="hidden" value="{{ $checkin }}">
				<input name="checkout" type="hidden" value="{{ $checkout }}">
				<input name="number_of_guests" type="hidden" value="{{ $number_of_guests }}">
				<input name="nights" type="hidden" value="{{ $nights }}">
				<input name="currency" type="hidden" value="{{ $result->property_price->code }}">
				<input name="booking_id" type="hidden" value="{{ $booking_id }}">
				<input name="booking_type" type="hidden" value="{{ $booking_type }}">

				@if($status == "" && $booking_type == "request")
					<div class="h2 pb-4 m-0 text-24">{{ trans('messages.listing_book.request_message') }}</div>
				@endif

				@if($booking_type == "instant"|| $status == "Processing" )
					<div class="col-md-12 p-0">
						<label for="exampleInputEmail1">{{ trans('messages.payment.country') }}</label>
					</div>
				
					<div class="col-sm-12 p-0 pb-3">
						<select name="payment_country" id="country-select" data-saving="basics1" class="form-control mb20">
							@foreach($country as $key => $value)
							<option value="{{ $key }}" {{ ($key == $default_country) ? 'selected' : '' }}>{{ $value }}</option>
							@endforeach
						</select>
					</div>
				
					<div class="col-sm-12 p-0">
						<label for="exampleInputEmail1">{{ trans('messages.payment.payment_type') }}</label>
					</div>

					<div class="col-sm-12 p-0 pb-3">
						<select name="payment_method" class="form-control mb20" required id="payment-method-select">
							<option value=""></option>

							<option value="qr" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
							{{ trans('QR') }} 
							</option>

							<option value="debit-credit" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
							{{ trans('Tarjeta de débito o crédito') }} 
							</option>

							@if($paypal_status->value == 1)
								<option value="paypal" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
								{{ trans('messages.payment.paypal') }} 
								</option>
							@endif

							@if($stripe_status->value == 1)  
								<option value="stripe" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
								{{ trans('messages.payment.stripe') }}
								</option>
							@else
								<!--<option value="">
								{{ trans('messages.payment.disable') }}
								</option>-->
							@endif 
							
							@if($razorpay_status->value == 1)
								<option value="razorpay" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
								{{ trans('messages.payment.razorpay') }} 
								</option>
							@endif
							
							<option value="3" data-payment-type="payment-method" @if($price_list->total > $wallet->total) disabled @endif>
								{{ trans('messages.experience.wallet') }} ( {!! moneyFormat( $wallet->currency->symbol, $wallet->total) !!} )
							</option>

						</select>
					</div>

					<div class="col-sm-12 p-0 pb-3 qr-container"></div>
					<div class="col-sm-12 p-0 pb-3 debit-credit-container"></div>
				
				@endif
		
					<div class="col-sm-12 p-0">
						<label for="message"></label>
					</div>

					<div class="col-sm-12 p-0 pb-3">
						<textarea name="message_to_host" placeholder="{{ trans('messages.trips_active.type_message') }}" class="form-control mb20" rows="7"></textarea>
					</div>
					
					<div class="col-sm-12 p-0 pb-3">
					    {{trans('messages.payment.cancel_desc')}} 
					     <a href="{{ url('terms-of-service') }}" class="secondary-text-color" target="_blank">{{trans('messages.sign_up.service_term')}} </a>, <a href="{{ url('guest-refund') }}" class="secondary-text-color" target="_blank">{{trans('messages.sign_up.refund_policy')}}</a>, <a href="{{ url('cancellation-policies') }}" class="secondary-text-color" target="_blank">{{trans('messages.listing_sidebar.cancellation_policy')}}</a>
                    </div>
			
					<div class="col-sm-12 p-0 text-right mt-4">
						<button id="payment-form-submit" type="submit" class="btn vbtn-outline-success text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3">
							<i class="spinner fa fa-spinner fa-spin d-none"></i>
							{{ ($booking_type == 'instant') ? trans('messages.listing_book.book_now') : 'Reservar' }}
						</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-4  mt-3 mb-5">
				<div class="card p-3">
					<a href="{{ url('/') }}/properties/{{ $result->id}}/{{ $result->slug}}">
						<img class="card-img-top p-2 rounded" src="{{ $result->cover_photo }}" alt="{{ $result->name }}" height="180px">
					</a>

					<div class="card-body p-2">
						<a href="{{ url('/') }}/properties/{{ $result->id}}/{{ $result->slug}}">
							<p class="text-16 font-weight-700 mb-0">{{ $result->name }}</p>
						</a>
						
						<p class="text-14 mt-2 text-muted mb-0">
							<i class="fas fa-map-marker-alt"></i>
							{{$result->property_address->address_line_1}}, {{ $result->property_address->state }}, {{ $result->property_address->country_name }}
						</p>
						
						@if($result->type=="property")
						<div class="border p-4 mt-4 text-center rounded-3">
							<p class="text-16 mb-0">
								<strong class="font-weight-700 secondary-text-color">{{ $result->property_type_name }}</strong> 
								{{trans('messages.payment.for')}}
								<strong class="font-weight-700 secondary-text-color">{{ $number_of_guests }} {{trans('messages.payment.guest')}}</strong> 
							</p>
							<div class="text-16"><strong>{{ date('D, M d, Y', strtotime($checkin)) }}</strong> to <strong>{{ date('D, M d, Y', strtotime($checkout)) }}</strong></div>					
						</div>
						@endif
						<div class="border p-4 rounded-3 mt-4">
							@if($result->type=="property")
	
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
							@endif
							
							@if($result->type=="experience" && $result->exp_booking_type=="1")
								<div class="d-flex justify-content-between text-16">
    								<div>
    									<p class="pl-4">{{trans('messages.header.check_in')}}</p>
    								</div>
    								<div>
    									<p class="pr-4">{{ date('D, M d, Y', strtotime($checkin)) }} </p>
    								</div>
							    </div>
								<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->property_price ) !!} x {{ $number_of_guests }} {{trans('messages.payment.guest')}}</p>
								</div>
								<div>
								    <?php $totol_price = $price_list->total_night_price * $number_of_guests;  ?>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $totol_price ) !!} </p>
								</div>
							</div>
							@endif
							
							@if($result->type=="experience" && $result->exp_booking_type=="2")
								<div class="d-flex justify-content-between text-16">
    								<div>
    									<p class="pl-4">{{trans('messages.header.check_in')}}</p>
    								</div>
    								<div>
    									<p class="pr-4">{{ date('D, M d, Y', strtotime($checkin)) }} </p>
    								</div>
							    </div>
							    
								<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->property_price ) !!} x {{ $number_of_guests }} {{trans('messages.payment.guest')}}</p>
								</div>
								<div>
								    <?php $totol_price = $price_list->total_night_price * $number_of_guests;  ?>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $totol_price ) !!} </p>
								</div>
							</div>
							
							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{trans('messages.experience.time_slot')}}</p>
								</div>
								<div>
									<p class="pr-4">{{ Session::get('time_slot') }} </p>
								</div>
							</div>
							@endif
							
							
							
							@if($result->type=="experience" && $result->exp_booking_type=="3")
								<div class="d-flex justify-content-between text-16">
    								<div>
    									<p class="pl-4">{{trans('messages.header.check_in')}}</p>
    								</div>
    								<div>
    									<p class="pr-4">{{ date('D, M d, Y', strtotime($checkin)) }} </p>
    								</div>
							    </div>
							    <div class="justify-content-between text-16">

								@if(session('cart'))	
    								<table class="table service-table ml-2">
                            			<thead class="thead-inverse">
                                			<tr>
                                				<th class="text-14">{{trans('messages.experience.packages_name')}}</th>
                                				<th class="text-14">{{trans('messages.experience.no_of_qty')}}</th>
                                				<th class="text-14">{{trans('messages.experience.price')}}</th>
                                			</tr>
                            			</thead>
                                         
                                            @foreach(session('cart') as $id => $details)
                                            <?php
                                                if($result->id == $details['property_id'])
                                                {
                                            ?>
                                    			<tbody>
                                        			<tr data-id="{{ $id }}">
                                        				<td class="text-14">{{ $details['name'] }}</td>
                                        				<td class="text-14">{{ $details['quantity'] }}</td>
                                        				<td class="text-14 pl-5">{!! $result->property_price->currency->symbol !!}{!! currency_fix($details['price'], $result->property_price->currency->code) !!}</td>
                                                        
                                        			</tr>  
                                        		</tbody>
                                        	<?php } ?>
                            			    @endforeach
    	                    	     </table>
	                    	     @endif
	
									<div>
									    
									    
									    @if(isset($family_query->price)) 
									    	<p class="pr-4">{!! $result->property_price->currency->symbol !!} {!! currency_fix($family_query->price, $result->property_price->currency->code) !!}</p>
										@endif
										
										@if($booking_id!="")
										        
										        @if(isset($booking_packages))
    									<div class="">
    									  
                                                 <table class="table service-table ml-3">
                                        			<thead class="thead-inverse">
                                            			<tr>
                                            				<th class="text-14">{{trans('messages.experience.packages_name')}}</th>
                                            				<th class="text-14">{{trans('messages.experience.no_of_qty')}}</th>
                                            				<th class="text-14">{{trans('messages.experience.price')}}</th>
                                            			</tr>
                                        			</thead>
        		                                        @foreach($booking_packages as $booking_packages)
                                                        <?php
                                                            $pid = $booking_packages->packages_id;
                                                            $query =  DB::table('family_package')->where('id', $pid)->first();
                                                        ?>
                                                		<tbody>
                                            			<tr data-id="">
                                            				<td class="text-14">{{ $query->title }}</td>
                                            				<td class="text-14">{{ $booking_packages->qty }}</td>
                                            				<td class="text-14">{!! $result->property_price->currency->symbol !!}{!! currency_fix($query->price, $result->property_price->currency->code) !!}</td>
                                                           
                                            			</tr>  
                                            		</tbody>
                                            		@endforeach
	                                        	</table>
    									</div>
									@endif
										        
										
    										@if(isset($family_price))
    										  <!--<p class="pr-4">{!! currency_fix($family_price, $result->property_price->currency->code) !!}</p>-->
    										@endif
										@endif
									</div>
								</div>
							@endif
						
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
										<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->additional_guest ) !!}</p>
									</div>
								</div>
							@endif
						
							@if($price_list->security_fee)
								<div class="d-flex justify-content-between text-16">
									<div>
										<p class="pl-4">{{trans('messages.payment.security_deposit')}}</p>
									</div>
	
									<div>
										<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol,  $price_list->security_fee ) !!}</p>
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

							<div class="d-flex justify-content-between font-weight-700">
								<div>
									<p class="pl-4">{{trans('messages.promotional_code')}}</p>
								</div>
	
								<div>
									<p class="pr-4">
										<input type="text" name="code">
									</p>
								</div>
							</div>

							<hr>
						
							<div class="d-flex justify-content-between font-weight-700">
								<div>
									<p class="pl-4">{{trans('messages.payment.total')}}</p>
								</div>
	
								<div>
									<p class="pr-4 total">{!! moneyFormat($result->property_price->currency->symbol, $price_list->total ) !!}</p>
									<input type="hidden" name="total" value="{!! moneyFormat($result->property_price->currency->symbol, $price_list->total ) !!}">
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<p class="exfont text-16">
							{{trans('messages.payment.paying_in')}}
							<strong><span id="payment-currency">{!! moneyFormat($currencyDefault->org_symbol,$currencyDefault->code) !!}</span></strong>.
							{{trans('messages.payment.your_total_charge')}}
							<strong><span id="payment-total-charge">{!! moneyFormat($currencyDefault->org_symbol, $price_eur) !!}</span></strong>.
							{{trans('messages.payment.exchange_rate_booking')}} {!! $currencyDefault->org_symbol !!} 1 to {!! moneyFormat($result->property_price->currency->org_symbol, $price_rate ) !!} {{ $result->property_price->currency_code }} ( {{trans('messages.listing_book.host_currency')}} ).
						</p>
					</div>
				</div>
				
			
		</div>
	</div>
</div>
@push('scripts')
<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
$('#payment-method-select').on('change', function(){
  var payment = $(this).val();
  if(payment == 'stripe' || payment == 'razorpay'){
    $('.cc-div').addClass('display-off');
    $('.paypal-div').addClass('display-off');
  }else {
    $('#paypal-text').html('You will be redirected to PayPal.');
    $('.cc-div').addClass('display-off');
    $('.paypal-div').removeClass('display-off');
  }
});

function setValues() {
	expiry = $('[name=cards]').find(':selected').attr('data-expiry-date');
	number = $('[name=cards]').find(':selected').attr('data-number');
	type = $('[name=cards]').find(':selected').attr('data-type');
	cvc = $('[name=cards]').find(':selected').attr('data-cvc');

	$('[name=card_expiration_date]').val(expiry);
	$('[name=card_number]').val(number);
	$('[name=card_type]').val(type);
	$('[name=card_cvn]').val(cvc);
	$('[name=save_card]').attr('checked', true);
}

$(document).ready(function() {
	$('[name=code]').keyup(function () {
		code = $(this).val();

		$.ajax({
			type: 'GET',
			data: {
				code: code,
			},
			url: '/promotion',
			success: function (response) {
				if (response) {
					total = $('[name=total]').val();
					total = total.replace(' $', '');
					total = total - (total * response.discount / 100);
					$('.total').html(total + ' $');
				}
			},
			error: function (error) {
				$('body').html(response);
			}
		});
	});

	$('[name=payment_method]').change(function () {
		value = $(this).val();
		$('.qr-container').html('');
		$('.debit-credit-container').html('');

		if (value == 'debit-credit') {
			$('.debit-credit-container').html(`
				<input type="hidden" name="amount" value="{{ $price_list->total }}">
				<input type="hidden" name="customer" value="{{ auth()->user()->id }}">
				<input type="hidden" name="linkser" value="1">

				<div class="row">
					<div class="col-md-4">
						<label for="card_type">{{ 'Tarjetas guardadas' }}</label>
						
						<select onchange="setValues()" name="cards" class="form-control">
							<option value=""></option>

							@foreach($cards as $card)
								<option data-expiry-date="{{ $card->expiry_date }}" data-number="{{ $card->number }}" data-cvc="{{ $card->cvc }}" data-type="{{ $card->type }}" value="{{ $card->id }}">{{ $card->name }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="row mt-3">
					<div class="col-md-2">
						<label for="card_type">{{ 'Tipo' }}</label>
						
						<select name="card_type" required class="form-control">
							<option value=""></option>
							<option value="001">Mastercard</option>
							<option value="002">Visa</option>
						</select>
					</div>

					<div class="col-md-4">
						<label for="card_number">{{ 'Número' }}</label>
						<input type="text" class="form-control" name="card_number" required>
					</div>

					<div class="col-md-2">
						<label for="card_number">{{ 'CVC/CVV' }}</label>
						<input type="text" class="form-control" name="card_cvn" required>
					</div>

					<div class="col-md-3">
						<label for="card_expiration_date">{{ 'Vencimiento' }}</label>
						<input type="text" class="form-control" name="card_expiration_date" placeholder="2027-01" required>
					</div>

					<div class="col-md-1">
						<label for="save_card">{{ 'Guardar' }}</label>

						<div>
							<input type="checkbox" name="save_card">
						</div>
					</div>
				</div>
			`);

			$('[name=card_expiration_date]').mask('0000-00');
		}

		if (value == 'qr') {
			$('.qr-container').html('<img src="https://media.tenor.com/tEBoZu1ISJ8AAAAC/spinning-loading.gif">');

			$.ajax({
				type: 'GET',
				url: '/qr',
				data: {
					amount: '{{ $price_list->total }}'
				},
				success: function (response) {
					$('.qr-container').html(response.img);
					$('.qr-container').append(response.id);
					$('.qr-container').append('<div>' + response.button + '</div>');
				},
				error: function (error) {
					console.log(error.responseText);
				}
			});
		}
	});

    $('#checkout-form').validate({        
        submitHandler: function(form)
        {
 			$("#payment-form-submit").on("click", function (e)
            {	
            	$("#payment-form-submit").attr("disabled", true);
                e.preventDefault();
            });


            $(".spinner").removeClass('d-none');
            $("#save_btn-text").text("{{trans('messages.users_profile.save')}} ..");
            return true;
        }
    });
});


$('#country-select').on('change', function() {
  var country = $(this).find('option:selected').text();
  $('#country-name-set').html(country);
})
</script>
@endpush 
@stop