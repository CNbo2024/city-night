	@extends('template')

	@section('main')
	<div class="margin-top-85">
		<div class="row m-0">
			<!-- sidebar start-->
			@include('users.sidebar')
			<!--sidebar end-->
				<div class="col-md-12 p-0">
		         @include('listing.sidebar')
		    </div>
			<div class="col-md-12 p-0">
				<div class="main-panel">
					<div class="row justify-content-center">
						<div class="col-md-6 p-0 sv_step_first">
							 <img src="{{ $eighth_step }}" class="img-fluid">
    			        <div>
               				<h3 class="text-center text-52 font-weight-700">{{trans('messages.listing_basic.property_booking')}}</h3>
                        </div>
						</div>

						<div class="col-md-6 pl-5 pr-4 mob-pd-lft">
							<form id="booking_id" method="post" action="{{url('listing/'.$result->id.'/'.$step)}}"  accept-charset='UTF-8'>
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
									
									<div class="row mt-5">
										<div class="col-md-6 pl-5 pr-5">
											<label>{{trans('messages.experience.check_in_after')}}</label>
											<select name="check_in_after" id="check_in_after" class="form-control text-16 mt-2" required>
											    <option value="">None</option>
                        						<option value="0" <?php if($result->check_in_after=="0"){ echo "selected"; } ?>>12:00 AM</option>
                        						<option value="1" <?php if($result->check_in_after=="1"){ echo "selected"; } ?>>01:00 AM</option>
                        						<option value="2" <?php if($result->check_in_after=="2"){ echo "selected"; } ?>>02:00 AM</option>
                        						<option value="3" <?php if($result->check_in_after=="3"){ echo "selected"; } ?>>03:00 AM</option>
                        						<option value="4" <?php if($result->check_in_after=="4"){ echo "selected"; } ?>>04:00 AM</option>
                        						<option value="5" <?php if($result->check_in_after=="5"){ echo "selected"; } ?>>05:00 AM</option>
                        						<option value="6" <?php if($result->check_in_after=="6"){ echo "selected"; } ?>>06:00 AM</option>
                        						<option value="7" <?php if($result->check_in_after=="7"){ echo "selected"; } ?>>07:00 AM</option>
                        						<option value="8" <?php if($result->check_in_after=="8"){ echo "selected"; } ?>>08:00 AM</option>
                        						<option value="9" <?php if($result->check_in_after=="9"){ echo "selected"; } ?>>09:00 AM</option>
                        						<option value="10" <?php if($result->check_in_after=="10"){ echo "selected"; } ?>>10:00 AM</option>
                        						<option value="11" <?php if($result->check_in_after=="11"){ echo "selected"; } ?>>11:00 AM</option>
                        						<option value="12" <?php if($result->check_in_after=="12"){ echo "selected"; } ?>>12:00 PM</option>
                        						<option value="13" <?php if($result->check_in_after=="13"){ echo "selected"; } ?>>01:00 PM</option>
                        						<option value="14" <?php if($result->check_in_after=="14"){ echo "selected"; } ?>>02:00 PM</option>
                        						<option value="15" <?php if($result->check_in_after=="15"){ echo "selected"; } ?>>03:00 PM</option>
                        						<option value="16" <?php if($result->check_in_after=="16"){ echo "selected"; } ?>>04:00 PM</option>
                        						<option value="17" <?php if($result->check_in_after=="17"){ echo "selected"; } ?>>05:00 PM</option>
                        						<option value="18" <?php if($result->check_in_after=="18"){ echo "selected"; } ?>>06:00 PM</option>
                        						<option value="19" <?php if($result->check_in_after=="19"){ echo "selected"; } ?>>07:00 PM</option>
                        						<option value="20" <?php if($result->check_in_after=="20"){ echo "selected"; } ?>>08:00 PM</option>
                        						<option value="21" <?php if($result->check_in_after=="21"){ echo "selected"; } ?>>09:00 PM</option>
                        						<option value="22" <?php if($result->check_in_after=="22"){ echo "selected"; } ?>>10:00 PM</option>
                        						<option value="23" <?php if($result->check_in_after=="23"){ echo "selected"; } ?>>11:00 PM</option>
                        					</select>
										</div>
										
										<div class="col-md-6 pl-5 pr-5">
											<label>{{trans('messages.experience.check_out_before')}}</label>
											<select name="check_out_before" id="check_out_before" class="form-control text-16 mt-2" required>
											    <option value="">None</option>
                        						<option value="0" <?php if($result->check_out_before=="0"){ echo "selected"; } ?>>12:00 AM</option>
                        						<option value="1" <?php if($result->check_out_before=="1"){ echo "selected"; } ?>>01:00 AM</option>
                        						<option value="2" <?php if($result->check_out_before=="2"){ echo "selected"; } ?>>02:00 AM</option>
                        						<option value="3" <?php if($result->check_out_before=="3"){ echo "selected"; } ?>>03:00 AM</option>
                        						<option value="4" <?php if($result->check_out_before=="4"){ echo "selected"; } ?>>04:00 AM</option>
                        						<option value="5" <?php if($result->check_out_before=="5"){ echo "selected"; } ?>>05:00 AM</option>
                        						<option value="6" <?php if($result->check_out_before=="6"){ echo "selected"; } ?>>06:00 AM</option>
                        						<option value="7" <?php if($result->check_out_before=="7"){ echo "selected"; } ?>>07:00 AM</option>
                        						<option value="8" <?php if($result->check_out_before=="8"){ echo "selected"; } ?>>08:00 AM</option>
                        						<option value="9" <?php if($result->check_out_before=="9"){ echo "selected"; } ?>>09:00 AM</option>
                        						<option value="10" <?php if($result->check_out_before=="10"){ echo "selected"; } ?>>10:00 AM</option>
                        						<option value="11" <?php if($result->check_out_before=="11"){ echo "selected"; } ?>>11:00 AM</option>
                        						<option value="12" <?php if($result->check_out_before=="12"){ echo "selected"; } ?>>12:00 PM</option>
                        						<option value="13" <?php if($result->check_out_before=="13"){ echo "selected"; } ?>>01:00 PM</option>
                        						<option value="14" <?php if($result->check_out_before=="14"){ echo "selected"; } ?>>02:00 PM</option>
                        						<option value="15" <?php if($result->check_out_before=="15"){ echo "selected"; } ?>>03:00 PM</option>
                        						<option value="16" <?php if($result->check_out_before=="16"){ echo "selected"; } ?>>04:00 PM</option>
                        						<option value="17" <?php if($result->check_out_before=="17"){ echo "selected"; } ?>>05:00 PM</option>
                        						<option value="18" <?php if($result->check_out_before=="18"){ echo "selected"; } ?>>06:00 PM</option>
                        						<option value="19" <?php if($result->check_out_before=="19"){ echo "selected"; } ?>>07:00 PM</option>
                        						<option value="20" <?php if($result->check_out_before=="20"){ echo "selected"; } ?>>08:00 PM</option>
                        						<option value="21" <?php if($result->check_out_before=="21"){ echo "selected"; } ?>>09:00 PM</option>
                        						<option value="22" <?php if($result->check_out_before=="22"){ echo "selected"; } ?>>10:00 PM</option>
                        						<option value="23" <?php if($result->check_out_before=="23"){ echo "selected"; } ?>>11:00 PM</option>
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
											<a href="{{ url('listing/'.$result->id.'/pricing') }}" class="btn btn-outline-danger secondary-text-color-hover text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3">
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