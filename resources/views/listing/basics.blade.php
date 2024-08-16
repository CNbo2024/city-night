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
			<div class="main-panel w-100">
				<div class="row">
					<div class="col-md-6 p-0 sv_step_first step-two">
                         <img src="{{ $second_step }}" class="img-fluid">
    			        <div>
               				<h3 class="text-center text-52 font-weight-700">{{trans('messages.listing_basic.basic_desc')}}</h3>
                        </div>

					</div>

					<div class="col-md-6  pr-5 p-4">
						<form method="post" action="{{url('listing/'.$result->id.'/'.$step)}}"  accept-charset='UTF-8' id="listing_bes">
							{{ csrf_field() }}
							<div class="form-row mt-4 rounded pb-4">
								<div class="form-group col-md-12 pb-3 pt-3 pl-0 pr-0">
									<h4 class="text-18 font-weight-700 pl-5">{{trans('messages.listing_basic.room_bed')}}</h4>
								</div>
							
								<div class="form-group col-md-6 pl-5 pr-5">
									<label for="inputState">{{trans('messages.listing_basic.bedroom')}}</label>
									<select name="bedrooms" id="basics-select-bedrooms"  class="form-control text-16 mt-2">
										@for($i=1;$i<=10;$i++)
										<option value="{{ $i }}" {{ ($i == $result->bedrooms) ? 'selected' : '' }}>
										{{ $i}}
										</option>
										@endfor 
									</select>
								</div>

								<div class="form-group col-md-6 pl-5 pr-5">
									<label for="inputState">{{trans('messages.listing_basic.bed')}}</label>
									<select name="beds" id="basics-select-beds"  class="form-control text-16 mt-2">
										@for($i=1;$i<=16;$i++)
											<option value="{{ $i }}" {{ ($i == $result->beds) ? 'selected' : '' }}>
											{{ ($i == '16') ? $i.'+' : $i }}
											</option>
										@endfor 
									</select>
								</div>

								<div class="form-group col-md-6 pl-5 pr-5">
									<label for="inputState">{{trans('messages.listing_basic.bathroom')}}</label>
									<select name="bathrooms" id="basics-select-bathrooms"  class="form-control text-16 mt-2">
										@for($i=1;$i<=8;$i+=1)
										<option class="bathrooms" value="{{ $i }}" {{ ($i == $result->bathrooms) ? 'selected' : '' }}>
										{{ ($i == '8') ? $i.'+' : $i }}
										</option>
										@endfor
									</select>
								</div>

								<div class="form-group col-md-6 pl-5 pr-5">
									<label for="inputState">{{trans('messages.listing_basic.bed_type')}}</label>

									@foreach($bed_type as $key => $value)
									<div>
										<input type="checkbox" name="bed_type[]" value="{{ $value }}"> {{ $value }}
									</div>
									@endforeach

									{{-- <select  name="bed_type"  class="form-control text-16 mt-2">
										@foreach($bed_type as $key => $value)
										<option value="{{ $key }}" {{ ($key == $result->bed_type) ? 'selected' : '' }}>{{ $value }}</option>
										@endforeach
									</select> --}}
								</div>
							</div>

							<div class="form-row mt-2 rounded pb-4">
								<div class="form-group col-md-12 pb-3 pt-3 pl-0 pr-0">
									<h4 class="text-18 font-weight-700 pl-5">{{trans('messages.listing_basic.listing')}}</h4>
								</div>
							
								<div class="form-group col-md-4 pl-5 pr-5">
									<label for="inputState">{{trans('messages.listing_basic.property_type')}}</label>
									<select name="property_type"  class="form-control text-16 mt-2">
										@foreach($property_type as $key => $value)
										<option value="{{ $key }}" {{ ($key == $result->property_type) ? 'selected' : '' }}>{{ $value }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group col-md-4 pr-5 mob-pd">
									<label for="inputState">{{trans('messages.listing_basic.room_type')}}</label>
									<select name="space_type" class="form-control text-16 mt-2">
										@foreach($space_type as $key => $value)
										<option value="{{ $key }}" {{ ($key == $result->space_type) ? 'selected' : '' }}>{{ $value }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group col-md-4 pr-5 mob-pd">
									<label for="inputState">{{trans('messages.listing_basic.accommodate')}}</label>
									<select name="accommodates" id="basics-select-accommodates" class="form-control text-16 mt-2">
									@for($i=1;$i<=16;$i++)
										<option class="accommodates" value="{{ $i }}" {{ ($i == $result->accommodates) ? 'selected' : '' }}>
										{{ ($i == '16') ? $i.'+' : $i }}
										</option>
									@endfor
									</select>
								</div>
							</div>
							<div class="col-md-12 mt-3">
    						    <hr class="step-hr">
    						</div>
							<div class="form-row float-right mt-4 mb-5">
								<div class="col-md-12 pr-0">
									<button type="submit" class="btn vbtn-default text-16 font-weight-700 pl-4 pr-4 pt-3 pb-3" id="btn_next"><i class="spinner fa fa-spinner fa-spin d-none" ></i>
										<span id="btn_next-text">{{trans('messages.listing_basic.next')}}</span> 
									</button>
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
		$('#listing_bes').validate({
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
            }
		});
	});
</script>
@endpush
