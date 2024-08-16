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
					<div class="row">
						<div class="col-md-6 p-0 sv_step_three">
							<img src="{{ $experience_fifth_img }}" class="img-fluid">
        			        <div>
                   				<h3 class="text-center text-52 font-weight-700">{{trans('messages.experience.experience_amenities')}}</h3>
                            </div>
						</div>

						<div class="col-md-6 mt-4 mt-sm-0 pl-4 pr-4 pb-5">
							<form id="amenities_id" method="post" action="{{url('experience/'.$result->id.'/'.$step)}}" accept-charset='UTF-8'>
								{{ csrf_field() }}
									<div class="col-md-12 p-0 mt-4 rounded-3">
											<div class="row">
												<div class="col-md-12 pl-4 mb-2">
													<h4 class="text-18 font-weight-700 pl-0 pr-0 pb-2">{{trans('messages.experience.inclusion')}}</h4>
												</div>

												<div class="col-md-12 pr-4 pt-0 pb-4">
													<div class="row">
														@foreach($inclusion as $inclusion)
													        <?php
																$sv_btype			= App\Models\Inclusion::where('id', $inclusion->id)->first();
																$temp_id1 			= $sv_btype->temp_id;
																$query				= App\Models\Inclusion::where('temp_id', $temp_id1)->get();
														    ?>
																<div class="col-xl-4 col-lg-6 p-0">
																	<label class="text-14 label-large label-inline amenity-label mb-3">
																		<input type="checkbox" value="<?php foreach($query as $query) { echo $query->id.','; } ?>" name="inclusion[]" {{ in_array($inclusion->id, $property_inclusion) ? 'checked' : '' }}>
																		<span>{{ $inclusion->name }}</span>
																	</label>
																</div>
														@endforeach
														<span class="ml-4"  id="at_least_one"><br></span>
													</div>
												</div>
												
													<div class="col-md-12 pl-4 mb-2">
													<h4 class="text-18 font-weight-700 pl-0 pr-0 pb-2">{{trans('messages.experience.exclusion')}}</h4>
												</div>

												<div class="col-md-12 pr-4 pt-0 pb-4">
													<div class="row">
														@foreach($exclusion as $exclusion)
													        <?php
																$sv_btype1			= App\Models\Exclusion::where('id', $exclusion->id)->first();
																$temp_id2 			= $sv_btype1->temp_id;
																
																$query1				= App\Models\Exclusion::where('temp_id', $temp_id2)->get();
														    ?>
																<div class="col-xl-4 col-lg-6 p-0">
																	<label class="text-14 label-large label-inline amenity-label mb-3">
																		<input type="checkbox" value="<?php foreach($query1 as $query1) { echo $query1->id.','; } ?>" name="exclusion[]" {{ in_array($exclusion->id, $property_exclusion) ? 'checked' : '' }}>
																		<span>{{ $exclusion->name }}</span>
																	</label>
																</div>
														@endforeach
														<span class="ml-4"  id="at_least_one"><br></span>
													</div>
												</div>
												
												
											</div>
									</div>
								
								<div class="col-md-12 mt-3">
    						        <hr class="step-hr">
    						    </div>
								<div class="col-md-12 p-0 mb-5">
									<div class="row justify-content-between mt-4">
										<div class="mt-4 ">
											<a data-prevent-default="" href="{{ url('experience/'.$result->id.'/location') }}" class="btn btn-outline-danger mlft-0 secondary-text-color-hover text-16 ml-5 font-weight-700 pl-5 pr-5 pt-3 pb-3" >
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