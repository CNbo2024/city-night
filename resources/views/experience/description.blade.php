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
					<div class="col-md-6 p-0 sv_step_first step-three">
                        <img src="{{ $experience_third_img }}" class="img-fluid">
    			        <div>
               				<h3 class="text-center text-52 font-weight-700">{{trans('messages.experience.experience_desc')}}</h3>
                        </div>
					</div>

					<div class="col-md-6 mt-4 mt-sm-0 pl-4 pr-4">
						<form method="post" id="list_des" action="{{url('experience/'.$result->id.'/'.$step)}}"  accept-charset='UTF-8'>
							{{ csrf_field() }}
							
							<div class="form-group col-md-12 pb-3 pt-3 mt-4">
								<h4 class="text-18 font-weight-700 pl-3">{{trans('messages.listing_sidebar.description')}}</h4>
							</div>
							<div class="container">
								<ul class="nav nav-tabs mt-3 ml-3">
									<li><a class="active" data-toggle="tab" href="#collapseen">En</a></li>
									  @foreach($languages_new as $key => $language)
									  @php  if($language->short_name == 'en'){continue;} @endphp 
									  
										<li><a data-toggle="tab" href="#collapse{{ $language->short_name }}">{{ $language->short_name }}</a></li>
									@endforeach
								</ul> 
								<div class="tab-content mt-5">
									
									<div id="collapseen" class="tab-pane fadein active">
										<div class="box-body">
											<div class="">
											
												<div class="row mt-4">
													<div class="col-md-12">
														<label>{{trans('messages.listing_description.listing_name')}} <span class="text-danger">*</span></label>
														<input type="text" name="name" id="name" class="form-control text-16 mt-2" value="{{ old('name', $description->properties->name)  }}" placeholder="" maxlength="100">
														<span class="text-danger">{{ $errors->first('name') }}</span>
													</div>
												</div>

												<div class="row mt-4">
													<div class="col-md-12">
														<label>{{trans('messages.listing_description.summary')}} <span class="text-danger">*</span></label>
														<textarea class="form-control text-16 mt-2" name="summary" rows="6" placeholder=""  ng-model="summary">{{ old('summary', $description->summary)  }} </textarea>
														<span class="text-danger">{{ $errors->first('summary') }}</span>
													</div>
												</div>

												
											</div>
										</div>
									</div>
								
								 @foreach($languages_new as $key => $language)
								  @php if($language->short_name == 'en'){continue;}  @endphp 
								  <?php
										$other_description = App\Models\PropertyMeta::where([ ['property_id',$result->id],['lang_id', $language->id ]])->first();
								 ?>
							  
								<div id="collapse{{ $language->short_name }}" class="tab-pane fade">
								  <div class="box-body">
										<input type="hidden" name="{{ $language->short_name }}[id]" value="{{$language->id}}">
										{{trans('messages.listing_description.listing_name')}} 
										<input type="text" class="form-control" value="@if(!empty($other_description->name)) {{ $other_description->name }} @endif" name="{{ $language->short_name }}[name1]" id="">
										<br>
										{{trans('messages.listing_description.summary')}}
										<textarea id="description" name="{{ $language->short_name }}[description1]" class="form-control" style="height: 200px">@if(!empty($other_description->summary)) {{ $other_description->summary }} @endif</textarea>
										
									</div>
								</div>
							  
								  @endforeach
								  </div>
								</div>

							
							
							
                            <div class="col-md-12">
                                <hr class="step-hr">
                            </div>
							<div class="col-md-12 p-0 mt-4 mb-5">
								<div class="row m-0 justify-content-between">
									<div class="mt-4 ml-5 mlft-0">
										<a  href="{{ url('experience/'.$result->id.'/basics') }}" class="btn btn-outline-danger secondary-text-color-hover text-16 font-weight-700  pt-3 pb-3 pl-5 pr-5">
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
@stop

@push('scripts')
<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#list_des').validate({
			rules: {
				name: {
					required: true
				},
				summary: {
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
				name: {
					required: "{{ __('messages.jquery_validation.required') }}",
				},
				summary: {
					required:  "{{ __('messages.jquery_validation.required') }}",
					maxlength: "{{ __('messages.jquery_validation.maxlength500') }}",
				} 
			}
		});
	});
</script>
@endpush