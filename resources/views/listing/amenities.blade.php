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
					<div class="row">
						<div class="col-md-6 p-0 sv_step_three">
							<img src="{{ $fifth_step }}" class="img-fluid">
        			        <div>
                   				<h3 class="text-center text-52 font-weight-700">{{trans('messages.listing_basic.property_amenities')}}</h3>
                            </div>
						</div>

						<div class="col-md-6 mt-4 mt-sm-0 pl-4 pr-4 pb-5">
							<form id="amenities_id" method="post" action="{{url('listing/'.$result->id.'/'.$step)}}" accept-charset='UTF-8'>
								{{ csrf_field() }}

								@foreach($amenities_type as $row_type)
									<div class="col-md-12 p-0 mt-4 rounded-3">
											<div class="row">
												<div class="col-md-12 pl-4 mb-2">
													<h4 class="text-18 font-weight-700 pl-0 pr-0 pb-2">{{ $row_type->name }}
														@if($row_type->name == 'Common Amenities')
														<span class="text-danger">*</span>
														@endif
													</h4>
													@if($row_type->description != '')
														<p class="text-muted">{{ $row_type->description }}</p>
													@endif
												</div>

												<div class="col-md-12 pl-4 pr-4 pt-0 pb-4">
													<div class="row">
														@foreach($amenities as $amenity)
													
															@if($amenity->type_id == $row_type->id)
																<div class="col-xl-4 col-lg-6">
																	<label class="text-14 label-large label-inline amenity-label">
																	<?php
																		$amenity_id  		= $amenity->id; 
																		$sv_btype			= App\Models\Amenities::where('id', $amenity_id)->first();
																		$temp_id1 			= $sv_btype->temp_id;
																		$query				= App\Models\Amenities::where('temp_id', $temp_id1)->get();
																	  ?>
																		<input type="checkbox" value="<?php foreach($query as $query) { echo $query->id.','; } ?>" name="amenities[]" data-saving="{{ $row_type->id }}" {{ in_array($amenity->id, $property_amenities) ? 'checked' : '' }}>
																		<span>{{ __($amenity->title) }}</span>
																	</label>

																	@if($amenity->description != '')
																		<span data-toggle="tooltip" class="icon" title="{{ $amenity->description }}"></span>
																	@endif
																</div>
															@endif
														
														@endforeach
														<span class="ml-4"  id="at_least_one"><br></span>
													</div>
												</div>
											</div>
									</div>
								@endforeach
								
								<div class="col-md-12 mt-3">
    						        <hr class="step-hr">
    						    </div>
								<div class="col-md-12 p-0 mb-5">
									<div class="row justify-content-between mt-4">
										<div class="mt-4 ">
											<a data-prevent-default="" href="{{ url('listing/'.$result->id.'/location') }}" class="btn btn-outline-danger mlft-0 secondary-text-color-hover text-16 ml-5 font-weight-700 pl-5 pr-5 pt-3 pb-3" >
											{{trans('messages.listing_description.back')}}
											</a>
										</div>

										<div class="mt-4">
											<button type="submit" class="btn vbtn-default text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3" id="btn_next"> <i class="spinner fa fa-spinner fa-spin d-none" ></i>
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
	@stop

	@push('scripts')
	<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#amenities_id').validate({
				rules: {
					'amenities[]': {
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
	                $("#btn_next-text").text("{{trans('messages.listing_basic.next')}}..");
	                return true;
	            },
				messages: {
					'amenities[]': {
						required: "{{ __('messages.jquery_validation.required') }}",
					}
				},
				
				groups: {
				amenities: "amenities[]"
				},
				errorPlacement: function(error, element) {
				if (element.attr("name") == "amenities[]") {
					error.insertAfter("#at_least_one");
				} else {
					error.insertAfter(element);
				}
				},
			});
		});
	</script>
@endpush