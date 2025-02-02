@extends('template')
@section('main')
<div class="margin-top-85">
	<div class="row m-0">
		@include('users.sidebar')
			<div class="col-md-12 p-0">
		         @include('listing.sidebar')
		    </div>
		<div class="col-md-12 p-0">
				<div class="row min-height">
					<div class="col-md-6 p-0 sv_step_three">
                         <img src="{{ $fourth_step }}" class="img-fluid">
    			        <div>
               				<h3 class="text-center text-52 font-weight-700">{{trans('messages.listing_basic.property_location')}}</h3>
                        </div>
					</div>

					<div class="col-md-6 mt-4 mt-sm-0 pl-4 pr-4 pb-5">
						<form id="lis_location" method="post" action="{{url('listing/'.$result->id.'/'.$step)}}" accept-charset='UTF-8'>
							{{ csrf_field() }}
							<div class="col-md-12 mt-4  rounded-3 pl-4 pl-sm-0 pr-4 pr-sm-0 mob-pd-0">
								<div class="form-group col-md-12 pt-3 mt-4 mt-sm-0 ">
									<h4 class="text-18 font-weight-700 pl-3">{{trans('messages.listing_sidebar.location')}} </h4>
								</div>

								<input type="hidden" name='latitude' id='latitude'>
								<input type="hidden" name='longitude' id='longitude'>
								<div class="row mt-4">
									<div class="col-md-6 pl-5 ">
										<label>{{trans('messages.listing_location.country')}} <span class="text-danger">*</span></label>
										<select id="basics-select-bed_type" name="country" class="form-control text-16 mt-2" id='country'>
											@foreach($country as $key => $value)
												@if($value == 'Bolivia')
													<option {{ $value == 'Bolivia' ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
												@endif
											@endforeach
										</select>
										<span class="text-danger">{{ $errors->first('country') }}</span>
									</div>
									<div class="col-md-6 pr-5 mob-pd">
										<label>{{trans('messages.listing_location.address_line_1')}} <span class="text-danger">*</span></label>
										<input type="text" name="address_line_1" id="address_line_1" value="{{ $result->property_address->address_line_1  }}" class="form-control text-16 mt-2" placeholder="House name/number + street/road">
										<span class="text-danger">{{ $errors->first('address_line_1') }}</span>
									</div>
								</div>

							
								<div class="row mt-4">
									<div class="col-md-12 pl-5 pr-5">
										<div id="map_view" class="map-view-location"></div>
									</div>
									<div class="col-md-12 mt-4 pl-5 pr-5">
										<p>You can move the pointer to set the correct map position</p>
										<span class="text-danger">{{ $errors->first('latitude') }}</span>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 mt-4 pl-5 pr-5">
										<label>{{trans('messages.listing_location.address_line_2')}}</label>
										<input type="text" name="address_line_2" id="address_line_2" value="{{ $result->property_address->address_line_2  }}" class="form-control text-16 mt-2" placeholder="Apt., suite, building access code">
									</div>

									<div class="col-md-6 mt-4 pl-5 pr-5">
										<label>{{trans('messages.listing_location.city_town_district')}}  <span class="text-danger">*</span></label>
										<input type="text" name="city" id="city" value="{{ $result->property_address->city  }} " class="form-control text-16 mt-2">
										<span class="text-danger">{{ $errors->first('city') }}</span>
									</div>

									<div class="col-md-6 mt-4 pl-5 pr-5">
										<label>{{trans('messages.listing_location.state_province')}} <span class="text-danger">*</span></label>
										<input type="text" name="state" id="state" value="{{ $result->property_address->state  }}" class="form-control text-16 mt-2">
										<span class="text-danger">{{ $errors->first('state') }}</span>
									</div>
								</div>
							</div>
							<div class="col-md-12 mt-5">
    						    <hr class="step-hr">
    						</div>

							<div class="col-md-12 p-0 mb-3">
								<div class="row justify-content-between">
									<div class="mt-4 ml-5 mlft-0">
										<a href="{{ url('listing/'.$result->id.'/details') }}" class="btn btn-outline-danger secondary-text-color-hover text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3">
											{{trans('messages.listing_description.back')}}
										</a>
									</div>

									<div class="mt-4">
										<button type="submit" class="btn vbtn-default text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3 pl-5 pr-5" id="btn_next"><i class="spinner fa fa-spinner fa-spin d-none" ></i>
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

<input type="hidden" id="latitudeV">
<input type="hidden" id="longitudeV">

@stop

@push('scripts')
	<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>
	<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ url('public/js/locationpicker.jquery.min.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			if (navigator.geolocation) {
			  navigator.geolocation.getCurrentPosition(function(position) {
			    /*$('#latitudeV').val(position.coords.latitude);
			    $('#longitudeV').val(position.coords.longitude);
			    console.log(position.coords.latitude, position.coords.longitude);*/

			    $('#map_view').locationpicker({
					location: {
						/*latitude: {{$result->property_address->latitude != '0'? $result->property_address->latitude:-17.7863 }},
						longitude: {{$result->property_address->longitude != '0'? $result->property_address->longitude:-63.1812 }}*/
						latitude: position.coords.latitude,
						longitude: position.coords.longitude
					},
					radius: 0,
					addressFormat: "",
					inputBinding: {
						latitudeInput: $('#latitude'),
						longitudeInput: $('#longitude'),
						locationNameInput: $('#address_line_1')
					},
					enableAutocomplete: true,
					onchanged: function (currentLocation, radius, isMarkerDropped) {
						var addressComponents = $(this).locationpicker('map').location.addressComponents;
						updateControls(addressComponents);
					},
					oninitialized: function (component) {
						var cb = function (event) {
				            $('#map_view').locationpicker('location', {
				                latitude: event.latLng.lat(),
				                longitude: event.latLng.lng()
				            });

				            var addressComponents = $(component).locationpicker('map').location.addressComponents;
							updateControls(addressComponents);
				        };
				        $('#map_view').locationpicker('subscribe', {
				            event: 'click',
				            callback: cb
				        });

						var addressComponents = $(component).locationpicker('map').location.addressComponents;
						updateControls(addressComponents);
					}
				});	
			  });


			} else {
			  alert("Geolocation is not supported by this browser.");
			}


		})

		function updateControls(addressComponents) {
			$('#street_number').val(addressComponents.streetNumber);
			$('#route').val(addressComponents.streetName);
			if (addressComponents.city) {
				$('#city').val(addressComponents.city);
			}
			$('#state').val(addressComponents.stateOrProvince);
			$('#postal_code').val(addressComponents.postalCode);
			$('#country').val(addressComponents.country);
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#lis_location').validate({
				rules: {
					address_line_1: {
						required: true,
						maxlength: 255
					},
					address_line_2: {
						maxlength: 255
					},
					city: {
						required: true
					},
					state: {
						required: true
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
				messages: {
					address_line_1: {
						required:  "{{ __('messages.jquery_validation.required') }}",
						maxlength: "{{ __('messages.jquery_validation.maxlength255') }}",
						},
					address_line_2: {
						required:  "{{ __('messages.jquery_validation.required') }}",
						maxlength: "{{ __('messages.jquery_validation.maxlength255') }}",
						},
					city: {
						required: "{{ __('messages.jquery_validation.required') }}",
					},
					state: {
						required: "{{ __('messages.jquery_validation.required') }}",
					}
				}
			});
		});
	</script>
@endpush