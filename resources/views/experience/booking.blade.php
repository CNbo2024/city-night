	@extends('template')

	@section('main')
	<div class="margin-top-85">
	  
		<div class="row m-0">
			<!-- sidebar start-->
			@include('users.sidebar')
			<!--sidebar end-->
			
			  <div class="col-md-12 p-0">
		         @include('experience.sidebar')
		    </div>
			<div class="col-md-12 p-0">
				<div class="main-panel">
					<div class="row justify-content-center">
						<div class="col-md-6 p-0 sv_step_first">
							 <img src="{{ $experience_eighth_img }}" class="img-fluid">
    			        <div>
               				<h3 class="text-center text-52 font-weight-700">{{trans('messages.experience.experience_booking')}}</h3>
                        </div>
						</div>

						<div class="col-md-6 pl-5 pr-4 mob-pd-lft">
							<form id="booking_id" method="post" action="{{url('experience/'.$result->id.'/'.$step)}}"  accept-charset='UTF-8'>
								{{ csrf_field() }}
								<div class="col-md-12 p-0 mt-4 border rounded pb-4 m-0">
									<div class="form-group col-md-12 main-panelbg pb-3 pt-3 pl-4">
											<h4 class="text-16 font-weight-700">{{trans('messages.listing_sidebar.booking')}}</h4>
									</div>
									<div class="row m-0 pl-5 pr-5">
										<div class="col-md-12 p-0">
											<h3>{{trans('messages.listing_book.booking_title')}} <span class="text-danger">*</span></h3>
											<p class="text-muted">{{trans('messages.listing_book.booking_data')}}.</p>
										</div>
									</div>
									
									<div class="row m-0">
										<div class="col-md-5 pl-5 pr-5">
											<label>{{trans('messages.listing_book.booking_type')}}</label>
											<select name="booking_type" id="booking_type" class="form-control text-16 mt-2">
												<option value="request" {{ ($result->booking_type == 'request') ? 'selected' : '' }}>{{trans('messages.listing_book.review_request')}}</option>
												<option value="instant" {{ ($result->booking_type == 'instant') ? 'selected' : '' }}>{{trans('messages.listing_book.guest_instant')}}</option>
											</select>
										</div>
									</div>
								</div>
								
								
								<div class="col-md-12 p-0 mt-4 border rounded pb-4 m-0">
									<div class="form-group col-md-12 main-panelbg pb-3 pt-3 pl-4">
											<h4 class="text-16 font-weight-700">{{trans('messages.listing_sidebar.terms')}}</h4>
									</div>
									<div class="row m-0 pl-5 pr-5">
										<div class="col-md-12 p-0">
											<h3>{{trans('messages.listing_sidebar.terms_desc')}} <span class="text-danger">*</span></h3>
										</div>
									</div>
									
									<div class="row m-0">
										<div class="col-md-12 pl-5 pr-5">
											<label>{{trans('messages.listing_sidebar.cancellation_policy')}}</label>
											<select name="cancellation" id="cancellation" class="form-control text-16 mt-2">
												<option value="Flexible" {{ ($result->cancellation == 'Flexible') ? 'selected' : '' }}> {{trans('messages.listing_sidebar.flexible')}} </option>
												<option value="Moderate" {{ ($result->cancellation == 'Moderate') ? 'selected' : '' }}> {{trans('messages.listing_sidebar.moderate')}}  </option>
												<option value="Strict" {{ ($result->cancellation == 'Strict') ? 'selected' : '' }}> {{trans('messages.listing_sidebar.strict')}} </option>

											</select>
										</div>
									</div>
								</div>
								
							
								
								<div class="col-md-12 mt-5">
    						        <hr class="step-hr">
    						    </div>

								<div class="col-md-12 mt-4 p-0 mb-5">
									<div class="row justify-content-between">
										<div class="mt-4">
											<a href="{{ url('experience/'.$result->id.'/pricing') }}" class="btn btn-outline-danger secondary-text-color-hover text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3">
												{{trans('messages.listing_description.back')}}
											</a>
										</div>

										<div class="mt-4">
											<button type="submit" class="btn vbtn-default text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3" id="btn_next"><i class="spinner fa fa-spinner fa-spin d-none" ></i>
												<span id="btn_next-text">{{trans('messages.listing_basic.next')}}</span>
												 
											</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@stop
	@push('scripts')
		<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
		
		<script type="text/javascript">
			$(document).ready(function () {
				$('#booking_id').validate({
					rules: {
						booking_type: {
							required: true,
						}
					},
					submitHandler: function(form)
		            {
				        $("#btn_next").on("click", function (e)
		                {	
		                	$("#btn_next").attr("disabled", true);
		                    e.preventDefault();
		                });


		                $(".spinner").removeClass('d-none');
		                $("#btn_next-text").text("{{trans('messages.listing_basic.next')}} ..");
		                return true;
		            },
					messages: {
						booking_type: {
							required:  "{{ __('messages.jquery_validation.required') }}",
						}
					}
				});
			});
		</script>
	@endpush