@extends('template')
@section('main')

<div class="margin-top-85 ">
	<div class="row m-0">
		@include('users.sidebar')
			<div class="main-panel row">
			    
			    <div class="col-md-6 p-0 sv_step_first">
			        <img src="{{ $experience_first_img }}" class="img-fluid">
			        <div>
       				<h3 class="text-center text-52 font-weight-700">{{trans('messages.experience.add_experience')}}</h3>
    				<p class="text-center text-22 pl-4 pr-4">{{ $site_name }} {{trans('messages.property.property_title')}}.</p>
                    </div>
			    </div>
			    
			    <div class="col-md-6">
    				<form id="list_space" method="post" action="{{url('experience/create')}}" class="mt-4" id="lys_form" accept-charset='UTF-8'>  
    					{{ csrf_field() }}
    					<input type="hidden" name='street_number' id='street_number'>
    					<input type="hidden" name='route' id='route'>
    					<input type="hidden" name='postal_code' id='postal_code'>
    					<input type="hidden" name='city' id='city'>
    					<input type="hidden" name='state' id='state'>
    					<input type="hidden" name='country' id='country'>
    					<input type="hidden" name='latitude' id='latitude'>
    					<input type="hidden" name='longitude' id='longitude'>
    					<div class="row p-4">
    				    
    						<div class="col-md-12">
    							<div class="form-group mt-4">
    								<label class="font-weight-600" for="exampleInputPassword1">{{trans('messages.property.city')}} <span class="text-danger">*</span></label>
    								<input type="text" class="form-control text-16" id="map_address" name="map_address" placeholder="">
    								@if ($errors->has('map_address')) <p class="error-tag">{{ $errors->first('map_address') }}</p> @endif
    								<div id="us3"></div>
    							</div>
    						</div>
    						<div class="col-md-12 mt-3">
    						    <hr class="step-hr">
    						</div>
    						<div class="col-md-12">
    							<div class="float-right">
    								<button type="submit" class="btn vbtn-default text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3 mt-4 mb-4" id="btn_next"> <i class="spinner fa fa-spinner fa-spin d-none" ></i>
    									<span id="btn_next-text">{{trans('messages.property.continue')}}</span>
    								</button>
    							</div>
    						</div>
    					</div>   
    				</form>
				</div>
				
			</div><!-- main panel end -->
			

	</div>
</div>
@stop
@push('scripts')
	<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>
	<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ url('public/js/locationpicker.jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ url('public/js/propertycreate.js') }}"></script>
	<script  type="text/javascript">
		$(document).ready(function () {
			$('#list_space').validate({
				rules: {
					property_type_id: {
						required: true
					},
					space_type: {
						required: true
					},
					accommodates: {
						required: true
					},
					map_address: {
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
	                $("#btn_next-text").text("{{trans('messages.property.continue')}}..");
	                return true;
	            },
				messages: {
					property_type_id: {
						required:  "{{ __('messages.jquery_validation.required') }}",
					},
					space_type: {
						required: "{{ __('messages.jquery_validation.required') }}",
					},
					accommodates: {
						required:  "{{ __('messages.jquery_validation.required') }}",
					},
					map_address: {
						required:  "{{ __('messages.jquery_validation.required') }}",
					},
				}
			});
		});
	</script>
@endpush
