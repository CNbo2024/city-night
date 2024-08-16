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
		
			<div class="main-panel w-100">
				<div class="row">
					<div class="col-md-6 p-0 sv_step_first step-two">
                         <img src="{{ $experience_second_img }}" class="img-fluid">
    			        <div>
               				<h3 class="text-center text-52 font-weight-700">{{trans('messages.experience.add_type_duration_of_your_space')}}</h3>
                        </div>

					</div>

					<div class="col-md-6  pr-5 p-4">
						<form method="post" action="{{url('experience/'.$result->id.'/'.$step)}}"  accept-charset='UTF-8' id="listing_bes" enctype='multipart/form-data'>
							{{ csrf_field() }}
						
							<div class="form-row mt-2 rounded pb-4">
							
								<div class="form-group col-md-12 pl-5 pr-5">
										<label>{{trans('messages.experience.title')}} <span class="text-danger">*</span></label>
										<input type="text" name="name" id="name" class="form-control text-16 mt-2" value="{{ $result->name }}" placeholder="" maxlength="100">
										<span class="text-danger">{{ $errors->first('name') }}</span>
								</div>
												
								<div class="form-group col-md-6 pl-5 pr-5">
									<label for="inputState">{{trans('messages.experience.experience_type')}}</label>
									<select name="experience_type"  class="form-control text-16 mt-2">
									@foreach($category as $category)
									<?php
										$sv_btype1			= App\Models\ExperienceCategory::where('id', $category->id)->first();
										$temp_id2 			= $sv_btype1->temp_id;
																
										$query1				= App\Models\ExperienceCategory::where('temp_id', $temp_id2)->get();
										$property_category = explode(',', $result->experience_type);
									?>
										<option value="<?php foreach($query1 as $query1) { echo $query1->id.','; } ?>" {{ in_array($category->id, $property_category) ? 'selected' : '' }} >{{ $category->name }}</option>
									@endforeach
									</select>
								</div>

							
								<div class="form-group col-md-6 pr-5 mob-pd">
									<label for="inputState">{{trans('messages.experience.max_people')}}</label>
									<input type="number" min="0" name="accommodates" id="basics-select-accommodates" class="form-control text-16 mt-2" value="{{ $result->accommodates }}">
								</div>
								
								<div class="form-group col-md-12 pr-5 pl-5 mob-pd">
									<label for="">{{trans('messages.experience.duration_ex')}}</label> 
									<input type="text" name="duration" id="duration" class="form-control text-16 mt-2" value="{{ $result->duration }}">
									
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
