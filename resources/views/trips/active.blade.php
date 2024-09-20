@extends('template')

@section('main')
<div class="margin-top-85">
	<div class="row m-0">
		@include('users.sidebar')
		<div class="col-lg-12">
			<div class="main-panel">
				<div class="container-fluid container-fluid-90 mt-5 svtrips">
					<span class="text-18 pt-4 pb-4 font-weight-700">
						{{trans('messages.users_dashboard.my_trips')}}
					</span>
					<div class="row">
						<div class="col-md-3 p-0 mb-3">
                            <div class="border mt-4 rounded-3">
                                
								<div>
									<form action="{{url('/trips/active')}}" method="POST" id="my-trip-form">
										{{ csrf_field() }}
										<select class="form-control room-list-status text-14 minus-mt-7" name="status" id="trip_select">
											<option value="All" {{ $status == "All" ? ' selected="selected"' : '' }}>{{trans('messages.filter.all')}}</option>
											<option value="Current" {{ $status == "Current" ? ' selected="selected"' : '' }}>{{trans('messages.filter.current')}}</option>
											<option value="Upcoming" {{ $status == "Upcoming" ? ' selected="selected"' : '' }}>{{trans('messages.filter.upcoming')}}</option>
											<option value="Pending" {{ $status == "Pending" ? ' selected="selected"' : '' }}>{{trans('messages.filter.pending')}}</option>
											<option value="Completed" {{ $status == "Completed" ? ' selected="selected"' : '' }}>{{trans('messages.filter.completed')}}</option>
											<option value="Expired" {{ $status == "Expired" ? ' selected="selected"' : '' }}>{{trans('messages.filter.expired')}}</option>
											<option value="Canceled" {{ $status == "Canceled" ? ' selected="selected"' : '' }}>{{trans('messages.canceled')}}</option>
										</select>
									</form>
								</div>
							</div>	
						
							
						</div>
				
				
					<div class="col-md-9 mb-3 p-0">
					    <div class="pl-5 db-content">
					@if(Session::has('message'))
					<div class="alert alert-success text-center" role="alert" id="alert">
                        <span id="messages">{{ Session::get('message') }}</span>
                    </div>
                    @endif
					@forelse($bookings as $booking)
						<?php 
							$property_id  	  = $booking->properties->id; 
							$exp_booking_type = $booking->properties->exp_booking_type;
							$booking_type     = $booking->properties->type;

                            if ($booking->created_at < $yesterday && $booking->status != 'Accepted') {
                                $booking->status = 'Expired';
                            }
                            
                            $expire_hours = $guest_payment_expiration_time * 24;
							
							if($booking->accepted_at!="" && $booking->status=="Processing")
							{
								$date = new DateTime($booking->accepted_at);
								$date->modify('+'.$expire_hours.' hours');
								$formatted_date = $date->format('Y-m-d H:i:s');
							
							} 
                        ?>

						<div class="row border border p-2  rounded-3 mt-4">
						    <script>
								<?php if($booking->accepted_at!="" && $booking->status=="Processing") { ?>
								var expiration_time<?php echo $booking->id; ?>  =  "<?php echo $formatted_date; ?>";
								var _second = 1e3, _minute = 60 * _second, _hour = 60 * _minute, _day = <?php echo $expire_hours; ?> * _hour, timer;
								
								function expirationTimeSet<?php echo $booking->id; ?>(){
									date_ele = new Date,
									present_time = new Date(date_ele.getUTCFullYear(), date_ele.getUTCMonth(), date_ele.getUTCDate(), date_ele.getUTCHours(), date_ele.getUTCMinutes(), date_ele.getUTCSeconds()).getTime(),
									expiration_time<?php echo $booking->id; ?> = new Date(this.expiration_time<?php echo $booking->id; ?>).getTime(),
									time_remaining = expiration_time<?php echo $booking->id; ?> - present_time;
									if (time_remaining < 0) 
									//return '';
									return clearInterval(interval), document.getElementById("countdown_1<?php echo $booking->id; ?>").innerHTML = "Expired!";
									else{
									var h = (Math.floor(time_remaining / this._day), Math.floor(time_remaining % this._day / this._hour)),
										m = Math.floor(time_remaining % this._hour / this._minute),
										s = Math.floor(time_remaining % this._minute / this._second);
										document.getElementById("countdown_1<?php echo $booking->id; ?>").innerHTML = h + ":", document.getElementById("countdown_1<?php echo $booking->id; ?>").innerHTML += m + ":", document.getElementById("countdown_1<?php echo $booking->id; ?>").innerHTML += s + "";
									}
								}

								var interval = setInterval(expirationTimeSet<?php echo $booking->id; ?>, 1e3)
								<?php } ?>
							</script>
						
							<div class="col-md-3 col-xl-4 p-2">
                                <div class="img-event">
                                    <a href="{{ url('/') }}/properties/{{ $booking->properties->id }}/{{ $booking->properties->slug }}">
                                        <img class="room-image-container200 rounded" src="{{ $booking->properties->cover_photo }}" alt="cover_photo">
                                    </a>  
                                </div>
							</div>
							
							<div class="col-md-9 col-xl-8 pl-2">
								<div class="row m-0 pr-4">
									<div class="col-md-10 col-9 col-sm-9 p-0">
										<a href="{{ url('/') }}/properties/{{ $booking->properties->id }}/{{ $booking->properties->slug}}">
											<p class="mb-0 text-18 text-color font-weight-700 text-color-hover pr-2">{{ $booking->properties->name}} </p>     
										</a>
									</div>

									<div>
										<span class="badge vbadge-success text-13 p-2 {{ $booking->status}}">{{ $booking->status}}</span>
									</div>
								</div>

								<div class="d-flex justify-content-between ">
									<div>
										<p class="text-14 text-muted mb-0">
											<i class="fas fa-map-marker-alt"></i>
											{{ $booking->properties->property_address->address_line_1 }}
										</p>
										<p class="text-14 mt-3"> 
											<i class="fas fa-calendar"></i> {{ date(' M d, Y', strtotime($booking->start_date)) }}  @if($booking_type=="property") -  {{ date(' M d, Y', strtotime($booking->end_date)) }} @endif
										</p>
		
										<p class="text-14 mt-3">
											<span class="{{$booking->status == 'Accepted' ? '' : 'd-none' }}">
												<a class="btn btn-success text-13" href="{{ url('/') }}/booking/receipt?code={{ $booking->code }}">
													<i class="fas fa-receipt"></i> {{trans('messages.trips_active.view_receipt')}}
												</a>
											</span>
											
											@if($booking->status == "Accepted")
    											<span class="">
    												<a class="btn btn-primary text-13" href="#">
    													<i class="fa fa-money-bill"></i> @if($booking->payment_method_id == "1" ) Paypal @elseif( $booking->payment_method_id == "2" ) Stripe @elseif( $booking->payment_method_id == "3" ) Wallet @elseif( $booking->payment_method_id == "5" ) Razorpay @endif
    												</a>
    											</span>
											@endif

											<span class="{{$booking->status == 'Processing' ? '' : 'd-none' }}">
												<a href="{{ url('/') }}/booking_payment/{{ $booking->id }}" class="btn-success text-13 p-2 rounded-4">
													<i class="fab fa-cc-amazon-pay"></i>  Make {{trans('messages.payment.payment')}}
												</a>
												
												<?php if($booking->accepted_at!="" && $booking->status=="Processing") { ?>
													<div class="pull-right mt-4">
														<span class="label label-info">
															<i class="far fa-clock"></i>
															{{ trans('messages.booking_detail.expire_in') }}
															<span class="countdown_timer hasCountdown text-danger"><span class="countdown_row countdown_amount" id="countdown_1<?php echo $booking->id; ?>"></span></span>
														</span>
													</div>
												<?php } ?>
											</span>
										</p>
										
										<?php
    										$now = new DateTime();
                                            $booking_start = new DateTime($booking->start_date);
                                        ?>
										
										<ul class="unstyled list-unstyled">
											  @if(@$booking->status != "Cancelled" && @$booking->status != "Declined" && @$booking->status != "Expired" && $now <  $booking_start)
											  <li class="row-space-1">
													<a data-rel="{{@$booking->id}}" href="#" class="booking_cancel btn-danger text-13 p-2 rounded-4">{{trans('messages.booking_my.cancel')}}</a>
											  </li>
											  @endif

											  <li class="row-space-1">
													<a data-rel="{{@$booking->id}}" href="#" class="booking_cancel btn-danger text-13 p-2 rounded-4">{{trans('messages.booking_my.cancel')}}</a>
											  </li>
										</ul>
										
										
									</div>

									<div class="pr-2 mt-5 mt-sm-0">
										<div class="align-self-center  mt-sm-0 w-100">
											<div class="row justify-content-center">
												<div class='img-round '>
													<a href="{{ url('/') }}/users/show/{{ $booking->host_id}}">
														<img src="{{ $booking->host->profile_src }}" alt="{{ $booking->host->first_name }}" class="rounded-circle img-70x70">
													</a>
												</div>
											</div>

											<p class="text-center font-weight-700 mb-0">
												<a href="{{ url('/') }}/users/show/{{ $booking->host_id}}" class="text-color text-color-hover">
													@if( $booking->host->display_name=="" )
													    {{ $booking->host->first_name}} 
													 @else
													 {{ $booking->host->display_name}}
													 @endif
												</a>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					@empty
						<div class="row jutify-content-center position-md-center w-100 p-4 mt-4 ">
							<div class="text-center w-100">
								<img src="{{ url('public/img/unnamed.png')}}"   alt="notfound" class="img-fluid">
								<p class="text-center"> {{trans('messages.message.empty_tripts')}} </p>
							</div>
						</div>
					@endforelse 

					<div class="row justify-content-between overflow-auto pb-3 mt-4 mb-5">
						{{ $bookings->appends(request()->except('page'))->links('paginate')}}
					</div>
					</div>
					</div>
					
					</div><!-- Row end -->
				</div><!-- container fluid -->
				
				

				<div class="modal" id="cancel-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header d-block">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">{{trans('messages.trips_active.cancel_booking')}}</h4>
        </div>
        <form accept-charset="UTF-8" action="{{ url('trips/guest_cancel') }}" id="cancel_reservation_form" method="post" name="cancel_reservation_form">
		{{ csrf_field() }}
          <div class="modal-body">
              <div id="decline_reason_container">
                <p>
                  {{trans('messages.trips_active.cancel_booking_data')}}
                </p>
                <p>
                  <strong>
                    {{trans('messages.trips_active.response_not_share')}}
                  </strong>
                </p>
                <div class="select">
                  <select id="cancel_reason" name="cancel_reason" class="form-control" required>
                    <option value="">{{trans('messages.trips_active.declining')}}</option>
                    <option value="no_longer_need_accommodations">{{trans('messages.trips_active.need_accommodation')}}</option>
                    <option value="travel_dates_changed">{{trans('messages.trips_active.travel_date_change')}}</option>
                    <option value="made_the_reservation_by_accident">{{trans('messages.trips_active.made_it_accident')}}</option>
                    <option value="I_have_an_extenuating_circumstance">{{trans('messages.trips_active.extenuating_circumstance')}}</option>
                    <option value="my_host_needs_to_cancel">{{trans('messages.trips_active.host_need_cancel')}}</option>
                    <option value="uncomfortable_with_the_host">{{trans('messages.trips_active.uncomfortable_host')}}</option>
                    <option value="place_not_okay">{{trans('messages.trips_active.place_not_expect')}}</option>
                    <option value="other">{{trans('messages.trips_active.other')}}</option>
                  </select>
                </div>

                <div id="cancel_reason_other_div" class="hide row-space-top-2">
                  <label for="cancel_reason_other">
                    {{trans('messages.trips_active.why_are_cancel')}}
                  </label>
                  <textarea class="form-control" id="decline_reason_other" name="decline_reason_other" rows="4"></textarea>
                </div>
              </div>
              <label for="cancel_message" class="row-space-top-2 mt-3">
                {{trans('messages.trips_active.type_message')}}
              </label>
              <textarea class="form-control" cols="40" id="cancel_message" name="cancel_message" rows="10"></textarea>
              <input type="hidden" name="id" id="booking_id" value="">
              <div class="col-sm-12 pt-0 mt-5 pb-3">
					    {{trans('messages.payment.cancel_desc')}} 
					     <a href="{{ url('terms-of-service') }}" class="secondary-text-color" target="_blank">{{trans('messages.sign_up.service_term')}} </a>, <a href="{{ url('guest-refund') }}" class="secondary-text-color" target="_blank">{{trans('messages.sign_up.refund_policy')}}</a>, <a href="{{ url('cancellation-policies') }}" class="secondary-text-color" target="_blank">{{trans('messages.listing_sidebar.cancellation_policy')}}</a>
                    </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="decision" value="decline">
            <!--<button type="submit" type="button" class="btn ex-btn">Cancel My Reservation</button>-->
            <input class="btn ex-btn btn-danger text-13 font-weight-600" id="cancel_submit" name="commit" type="submit" value="{{trans('messages.trips_active.cancel_my_booking')}}">
            <button type="button" class="btn btn-primary text-13 font-weight-600" data-dismiss="modal">{{trans('messages.trips_active.close')}}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  
  
				
			</div>
		</div>
	</div>
</div>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).on('change', '#trip_select', function(){
            $("#my-trip-form").trigger("submit"); 
        });
        
        $(document).ready(function()
        {
            document.getElementById('trip_select').size=7;
        });
    </script>
	
<script type="text/javascript">
  $('.booking_cancel').on('click', function(){
    var booking_id = $(this).attr('data-rel');
    $('#booking_id').val(booking_id);
    $('#cancel-modal').modal();
  });
</script>

@endpush