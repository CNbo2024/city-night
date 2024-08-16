@extends('template')

@section('main')
<div class="margin-top-85">
    <div class="row m-0">
        @include('users.sidebar')
        <div class="col-lg-12">
                <div class="container-fluid container-fluid-90 mt-5 mybooking">
                    <span class="text-18 pt-4 pb-4 font-weight-700">{{trans('messages.booking_my.booking')}}</span>

                    <div class="row">

                        <div class="col-md-3 p-0 mb-3">
                            <div class="mt-4 rounded-3 border">
                                        <!--<div class="pl-3 mt-3 pb-3">
                                            <span class="text-14 font-weight-700">{{trans('messages.users_dashboard.sort_by')}}</span>
                                        </div>-->

                                        <div>
                                            <form action="{{ url('/my-bookings') }}" method="POST" id="my-bookings-form" > 
                                                {{ csrf_field() }}
                                                <select class="form-control room-list-status text-14 minus-mt-6" name="status" id="booking_select">
                                                    <option value="All" {{ $status == "All" ? ' selected="selected"' : '' }}>{{trans('messages.filter.all')}}</option>
                                                    <option value="Current" {{ $status == "Current" ? ' selected="selected"' : '' }}>{{trans('messages.filter.current')}}</option>
                                                    <option value="Upcoming" {{ $status == "Upcoming" ? ' selected="selected"' : '' }}>{{trans('messages.filter.upcoming')}}</option>
                                                    <option value="Pending" {{ $status == "Pending" ? ' selected="selected"' : '' }}>{{trans('messages.filter.pending')}}</option>
                                                    <option value="Completed" {{ $status == "Completed" ? ' selected="selected"' : '' }}>{{trans('messages.filter.completed')}}</option>
                                                    <option value="Expired" {{ $status == "Expired" ? ' selected="selected"' : '' }}>{{trans('messages.filter.expired')}}</option>
                                                </select>
                                            </form>
                                        </div>
                                
                            </div>
                        </div>
                   
                    
                    <div class="col-md-9 p-0">
                         <div class="pl-5 db-content">
                        @if(Session::has('message'))
                            <div class="alert {{ Session::get('alert-class') }}  alert-dismissible fade show text-center" role="alert">
                                {{ Session::get('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
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
                            ?>
    
                            <div class="row border border p-2  rounded-3 mt-4">
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
                                            <a href="{{ url('/') }}/properties/{{ $booking->properties->id }}/{{ $booking->properties->slug }}">
                                                <p class="mb-0 text-18 text-color font-weight-700 text-color-hover pr-2">{{ $booking->properties->name }}</p>     
                                            </a>
                                        </div>
    
                                        <div>
                                            <span class="badge vbadge-success text-13 p-2 {{ $booking->status}}">{{ $booking->status }}</span>
                                        </div>
                                    </div>
    
                                    <div class="d-flex justify-content-between sv_booking_dflex">
                                        <div>
                                            <p class="text-14 text-muted mb-0">
                                                <i class="fas fa-map-marker-alt"></i>
                                                @if(isset($booking->properties->property_address->address_line_1))
                                                    {{ $booking->properties->property_address->address_line_1 }}
                                                @endif
                                            </p>
    
                                            <p class="text-14 mt-3"> 
                                                <i class="fas fa-calendar"></i> {{ date(' M d, Y', strtotime($booking->start_date)) }}  @if($booking_type=="property") -  {{ date(' M d, Y', strtotime($booking->end_date)) }} @endif
                                            </p>
            
                                            <p class="text-14 mt-3">
                                                <span class="{{$booking->status == 'Pending' ? '' : 'd-none' }}">
                                                    <a class="btn-danger p-2 rounded-3" href="{{ url('/') }}/booking/{{ $booking->id }}">
                                                        <i class="fas fa-check"></i> {{ trans('messages.email_template.accept/decline') }}
                                                    </a>
                                                </span>
                                                <span class="{{ $booking->status == 'Accepted' ? '' : 'd-none' }}">
                                                    <a class="btn-success p-2 rounded-3" href="{{ url('/') }}/booking/receipt?code={{ $booking->code }}">
                                                        <i class="fas fa-receipt"></i> {{ trans('messages.trips_active.view_receipt') }}
                                                    </a>
                                                </span>
                                            </p>
																						
											 <ul class="list-unstyled">
											    @if(@$booking->status != "Cancelled" && @$booking->status != "Declined" && @$booking->status != "Expired" && @$booking->status != "Accepted")
													  <li>
														  <a data-rel="{{@$booking->id}}" href="#" class="text-danger" id="reservation_cancel<?php echo $booking->id ?>">{{trans('messages.booking_my.cancel')}}</a>
													  </li>
												  @endif
											</ul>
				
                                        </div>
    
                                        <div class="pr-2 mt-5 mt-sm-0">
                                            <div class="align-self-center  mt-sm-0 w-100">
                                                <div class="row justify-content-center">
                                                    <div class='img-round'>
                                                        <a href="{{ url('/') }}/users/show/{{ $booking->user_id }}">
                                                            <img src="{{ $booking->users->profile_src }}" alt="{{ $booking->users->first_name }}" class="rounded-circle img-70x70">
                                                        </a>
                                                    </div>
                                                </div>
                                                <p class="text-center font-weight-700 mb-0">
                                                    <a href="{{ url('/') }}/users/show/{{ $booking->user_id }}" class="text-color text-color-hover">
                                                       @if( $booking->users->display_name=="")
                                                            {{ $booking->users->first_name }}
                                                        @else
                                                            {{ $booking->users->display_name }}
                                                        @endif
                                                    </a>
                                                </p>
                                                
                                                <p class="w-188 text-center">
                                                    <a class="btn-primary text-13 p-2 rounded-3" href="{{ url('/') }}/inbox"><i class="fa fa-comments" aria-hidden="true"></i>  {{ trans('messages.trips_active.send_msg') }}</a>
                                                </p>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							@push('scripts')
  
							<script type="text/javascript">
							  $('#reservation_cancel<?php echo $booking->id ?>').on('click', function(){
								var booking_id = $(this).attr('data-rel');
								$('#booking_id').val(booking_id);
								$('#cancel-modal<?php echo $booking->id ?>').modal();
							  })
							</script>
							@endpush
							
							<div class="modal" id="cancel-modal<?php echo $booking->id ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header d-block">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title font-weight-700 text-center">{{ trans('messages.modal.cancel_this_booking') }}</h4>
        </div>
        <form accept-charset="UTF-8" action="{{ url('booking/host_cancel') }}" id="cancel_reservation_form" method="post" name="cancel_reservation_form">
          {{ csrf_field() }}
		  <div class="modal-body">
              <div id="decline_reason_container">
                <p>
                  {{ trans('messages.modal.what_reason_cancelling') }}
                </p>
                <div class="select">
                  <select id="cancel_reason" name="cancel_reason" class="form-control" required>
                    <option value="">{{ trans('messages.modal.why_are_you_cancelling') }}</option>
                    <option value="i_am_uncomfortable_with_guest">{{ trans('messages.modal.i_am_uncomfortable') }}</option>
                    <option value="no_longer_available">{{ trans('messages.modal.place_no_longer_available') }}</option>
                    <option value="offer_a_different_listing">{{ trans('messages.modal.offer_a_different_listing') }}</option>
                    <option value="need_maintenance">{{ trans('messages.modal.need_maintenance') }}</option>
                    <option value="I_have_an_extenuating_circumstance">{{ trans('messages.modal.extenuating_cicumstance') }}</option>
                    <option value="my_guest_needs_to_cancel">{{ trans('messages.modal.guest_needs_cancel') }}</option>
                    <option value="other">{{ trans('messages.modal.other') }}</option></select>
                  </select>
                </div>

               
              </div>
              <label for="cancel_message" class="row-space-top-2 mt-3">
                {{ trans('messages.modal.messsage_guest') }}...
              </label>
              <textarea class="form-control" cols="40" id="cancel_message" name="cancel_message" rows="10"></textarea>
              <input type="hidden" name="id" id="booking_id" value="<?php echo $booking->id; ?>">
              <div class="col-sm-12 p-0 mt-5 pb-3">
					    {{trans('messages.payment.cancel_desc')}} 
					     <a href="{{ url('terms-of-service') }}" class="secondary-text-color" target="_blank">{{trans('messages.sign_up.service_term')}} </a>, <a href="{{ url('guest-refund') }}" class="secondary-text-color" target="_blank">{{trans('messages.sign_up.refund_policy')}}</a>, <a href="{{ url('cancellation-policies') }}" class="secondary-text-color" target="_blank">{{trans('messages.listing_sidebar.cancellation_policy')}}</a>
                    </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="decision" value="decline">
            <input class="btn ex-btn text-13 btn-danger font-weight-600" id="cancel_submit" name="commit" type="submit" value="{{ trans('messages.trips_active.cancel_my_booking') }}">
            <button type="button" class="btn btn-primary text-13 font-weight-600" data-dismiss="modal">{{ trans('messages.trips_active.close') }}</button>
          </div>
        </form>
      </div>

    </div>
  </div>

                        
                         
                         
                        @empty
                            <div class="row jutify-content-center w-100 position-md-center p-4 mt-4">
                                <div class="text-center w-100">
                                    <img src="{{ url('public/img/unnamed.png') }}"   alt="notfound" class="img-fluid">
                                    <p class="text-center">{{ trans('messages.booking_my.no_booking') }}.</p>
                                </div>
                            </div>
                        @endforelse 

                    <div class="row justify-content-between overflow-auto pb-3 mt-4 mb-5">
                        {{ $bookings->appends(request()->except('page'))->links('paginate') }} 
                    </div>
                    
                    </div>
                    </div><!-- col-md-9 -->
                    
                    </div><!-- row end -->
                </div><!-- container end -->
				
						
				
            
			
			
			
			
			
			
			
        </div>
    </div>
</div>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).on('change', '#booking_select', function(){
            $("#my-bookings-form").trigger("submit"); 
        });
        
        $(document).ready(function()
        {
            document.getElementById('booking_select').size=6;
        });
    </script>
	
	<script type="text/javascript">
  $('#reservation_cancel').on('click', function(){
	  alert("hoi");
    var booking_id = $(this).attr('data-rel');
    $('#booking_id').val(booking_id);
    $('#cancel-modal').modal();
  })
</script>
@endpush
