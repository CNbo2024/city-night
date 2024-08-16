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
					<div class="row">
						<div class="col-md-4 p-0 sv_step_first">
						    <img src="{{ $experience_seventh_img }}" class="img-fluid">
            			        <div>
                       				<h3 class="text-center text-52 font-weight-700">{{trans('messages.listing_basic.property_price')}}</h3>
                                </div>
						</div>
						<?php
							$start = strtotime('00:00');
							$end   = strtotime('23:30');
						?>
						<div class="col-md-8 mt-4 mt-sm-0 pl-4 pr-4 mb-5 pb-5">
							<form id="lis_pricing" method="post" action="{{url('experience/'.$result->id.'/'.$step)}}" accept-charset='UTF-8' enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="form-row mt-4 border rounded pb-4 m-0">
									<div class="form-group col-md-12 main-panelbg pb-3 pt-3 pl-4">
											<h4 class="text-16 font-weight-700">{{trans('messages.experience.price')}}</h4>
									</div>

									<div class="form-group col-lg-4 pl-5 pr-5">
										<label for="listing_price_native">
											{{trans('messages.experience.booking_type')}} <span class="text-danger">*</span>
										</label>
										<div class="form-groupw-100">
											<div class="input-group-prepend">
												<select class="form-control" id="exp_booking_type" name="exp_booking_type">
													<option value="1" <?php if($result->exp_booking_type == "1") echo "selected"; ?> >{{trans('messages.experience.date')}}</option>
													<option value="2" <?php if($result->exp_booking_type == "2") echo "selected"; ?>>{{trans('messages.experience.date_time')}}</option>
													<option value="3" <?php if($result->exp_booking_type == "3") echo "selected"; ?>>{{trans('messages.experience.packages')}}</option>
												</select>
											</div>
										</div>
									</div>
									
									<div class="form-group col-lg-4 pl-5 pr-5">
										<label for="inputPassword4">{{trans('messages.listing_price.currency')}}</label>
										<select id='price-select-currency_code' name="currency_code" class='form-control text-16 mt-2'>
											@foreach($currency as $key => $value)
												<option value="{{$key}}" {{$result->property_price->currency_code == $key?'selected':''}}>{{$value}}</option>
											@endforeach
										</select>
									</div>

									
									<div class="form-group col-lg-4 pl-5 pr-5 sv_price_div" <?php if($result->exp_booking_type == "3") { ?> style="display:none" <?php } else { ?> style="display:block"  <?php } ?> >
										<label for="listing_price_native">
											{{trans('messages.experience.price')}}
											<span class="text-danger">*</span>
										</label>
										<div class="form-groupw-100">
											<div class="input-group-prepend ">
												<span class="input-group-text line-height-2-4 text-16">{!! $result->property_price->currency->org_symbol !!}</span>
											
												<input type="text" id="price-night" value="{{ ($result->property_price->original_price == 0) ? '' : $result->property_price->original_price }}" name="price"  class="money-input w-100 text-16" >
											</div>
											<span class="text-danger" id="price-error">{{ $errors->first('price') }}</span>
										</div>
									</div>
									
									<div class="col-md-12 mt-4 pb-5  pl-sm-0 pr-sm-0 mob-pd-0" id="family_div" <?php if($result->exp_booking_type == "3") { ?> style="display:block" <?php } else { ?> style="display:none" <?php } ?>>
                                    			<div class="form-group col-md-12 pt-3 mt-sm-0 ">
                                    				<h4 class="text-18 font-weight-700 pl-3">{{trans('messages.experience.packages')}}</h4>
                                    			</div>
											<div class="field_wrapper2 row">
													<?php foreach($family as $family) { ?>
													<div class="row col-md-6 pb-3 pt-3" >
														<div class="col-md-12 sv_family_package">
														 
														    <input type="hidden" id="fid" name="fid[]" value="{{ $family->id }}"> 

														    <label>{{ trans("messages.experience.packages_name") }}</label>
															<input type="text" id="title" name="title[]" value="{{ $family->title }}" class="form-control mr-3 mt-3" placeholder="Title">
															
															<label>{{trans("messages.experience.price")}}</label>
															<input type="number" min="0" id="fprice" name="fprice[]" value="{{ $family->price }}" class="form-control mr-3 mt-3" placeholder="price">
															
															<label>{{trans("messages.experience.no_adults")}}</label>
															<input type="number" min="0" id="adults" name="adults[]" value="{{ $family->adults }}" class="form-control mr-3 mt-3" placeholder="No. Adults">
															
															<label>{{trans("messages.experience.child")}}</label>
															<input type="number" min="0" id="children" name="children[]" value="{{ $family->children }}" class="form-control mr-3 mt-3" placeholder="No. Children">
															
															<label>{{trans("messages.experience.infants")}}</label>
															<input type="text"  id="infants" name="infants[]" value="{{ $family->infants }}" class="form-control mr-3 mt-3" placeholder="No. Infants">
                                                            
                                                            <label>{{trans("messages.experience.full_details")}}</label>
															<textarea id="full_details" name="full_details[]" class="form-control mr-3 mt-3" placeholder="{{trans('messages.experience.full_details_placeholder')}}">{{ $family->full_details }}</textarea>
                                                           
                                                            <label>{{trans("messages.experience.your_itinerary")}}</label>
															<textarea id="itinerary" name="itinerary[]" class="form-control mr-3 mt-3">{{ $family->itinerary }}</textarea>
                                                            
                                                            
                                                            <label>{{trans("messages.experience.photo")}}</label>
															<input type="file" id="file" name="file[]" class="form-control mr-3 mt-3" value="">
															@if($family->file!="")
															    <img src="{{url('public/images/experience/'.$result->id.'/'.$family->file)}}" width="100px">
															@endif
															
														    <a href="javascript:void(0);" class="remove_button2 btn btn-sm text-13 btn-danger">{{trans('messages.experience.remove')}}</a>
														  
														</div>
														
													</div>
												
													<?php } ?>
													<div style="clear:both;"></div>

													<div class="col-md-12 mt-3">
													    <a href="javascript:void(0);" class="add_button2 btn btn-sm btn-primary text-14 w-188" title="Add field">{{trans('messages.experience.add')}} </a>
                                                    </div>
												</div>
										</div>
									
									
									
                                    	<div class="col-md-12 mt-4 pb-5  pl-sm-0 pr-sm-0 mob-pd-0" id="time_div" <?php if($result->exp_booking_type == "2") { ?> style="display:block" <?php } else { ?> style="display:none" <?php } ?>>
                                    			<div class="form-group col-md-12 pt-3 mt-sm-0 ">
                                    				<h4 class="text-18 font-weight-700 pl-3">{{trans('messages.experience.time')}}</h4>
                                    			</div>
												<div class="field_wrapper1">
													<?php foreach($time as $time) { ?>
													<div class="row pb-3 pt-3" >
														<div class="col-md-12 d-flex">
    													    <input type="hidden" id="tid" name="tid[]" value="{{ $time->id }}"> 

															<select id='start_time' name="start_time[]" class='form-control text-16 w-50 mr-3'>
																 <option value="">{{trans('messages.experience.select')}}</option>
																<?php
																	for ($i=$start;$i<=$end;$i = $i + 30*60)
																	{
																?>
																<option value="<?php echo date('h:i A',$i); ?>" <?php if(date('h:i A',$i) == $time->start_time) { echo "selected"; } ?>><?php echo date('h:i A',$i); ?></option>
																<?php    
																	}
																?>
															</select>
														
															<select id='end_time' name="end_time[]" class='form-control text-16 w-50 mr-3'>
																 <option value="">{{trans('messages.experience.select')}}</option>
																<?php
																	for ($i=$start;$i<=$end;$i = $i + 30*60)
																	{
																?>
																<option value="<?php echo date('h:i A',$i); ?>" <?php if(date('h:i A',$i) == $time->end_time) { echo "selected"; } ?>><?php echo date('h:i A',$i); ?></option>
																<?php    
																	}
																?>
															</select>	
															<a href="javascript:void(0);" class="remove_button1 btn btn-sm btn-danger text-13 pt-3">{{trans('messages.experience.remove')}}</a>
														</div>
														
													</div>
													<?php } ?>
													<div class="col-md-12">
													    <a href="javascript:void(0);" class="add_button1 btn btn-sm btn-primary text-13 pl-3 pr-3" title="Add field">{{trans('messages.experience.add')}}</a>
                                                    </div>
												</div>
										</div>
									

								</div>

							
								
								<!--<div class="row">
                                    	<div class="col-md-12 border mt-4 pb-5 rounded-3 pl-sm-0 pr-sm-0 mob-pd-0">
                                    			<div class="form-group col-md-12 main-panelbg pb-3 pt-3 mt-sm-0 ">
                                    				<h4 class="text-18 font-weight-700 pl-3">Itinerary</h4>
                                    			</div>
												<div class="field_wrapper">
													<?php foreach($itinerary as $itinerary) { ?>
													<div class="row pb-3 pt-3" style="border-bottom:1px solid #ddd;">
														<div class="col-md-12">
															<label for="">Title</label> 
															<input class="form-control text-16 mt-2" type="text" name="field_name[]" value="{{ $itinerary->title }}">
														
															<label for="">Description</label> 
															<textarea class="form-control text-16 mt-2" id="description" name="description[]" >{{ $itinerary->description }}</textarea>
															<a href="javascript:void(0);" class="remove_button"><img src="http://demos.codexworld.com/add-remove-input-fields-dynamically-using-jquery/images/remove-icon.png"/></a>
														</div>
														
													</div>
													<?php } ?>
													<a href="javascript:void(0);" class="add_button" title="Add field"><img src="http://demos.codexworld.com/add-remove-input-fields-dynamically-using-jquery/images/add-icon.png"/></a>

												</div>
										</div>
								</div>-->

								<div class="row justify-content-between mb-3">
									<div class="mt-4">
										<a  data-prevent-default="" href="{{ url('experience/'.$result->id.'/photos') }}" class="btn btn-outline-danger secondary-text-color-hover text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3 pl-5 pr-5">
											{{trans('messages.listing_description.back')}}
										</a>
									</div>

									<div class="mt-4">
										<button type="submit" class="btn vbtn-default text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3 pl-5 pr-5" id="btn_next"> <i class="spinner fa fa-spinner fa-spin d-none" ></i> <span id="btn_next-text">{{trans('messages.listing_basic.next')}}</span>
										
										</button>
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
			$(document).ready(function(){
				var maxField = 10; //Input fields increment limitation
				var addButton = $('.add_button'); //Add button selector
				var wrapper = $('.field_wrapper'); //Input field wrapper
				var fieldHTML = '<div class="row pb-3 pt-3"><div class="col-md-12"><label>Title</label> <input class="form-control" type="text" name="field_name[]" value=""/><label for="">Description</label> <textarea class="form-control text-16 mt-2" id="description" name="description[]" ></textarea><a href="javascript:void(0);" class="remove_button"><img src="http://demos.codexworld.com/add-remove-input-fields-dynamically-using-jquery/images/remove-icon.png"/></a></div></div>'; //New input field html 
				var x = 1; //Initial field counter is 1
				
				//Once add button is clicked
				$(addButton).click(function(){
					//Check maximum number of input fields
					if(x < maxField){ 
						x++; //Increment field counter
						$(wrapper).append(fieldHTML); //Add field html
					}
				});
				
				//Once remove button is clicked
				$(wrapper).on('click', '.remove_button', function(e){
					e.preventDefault();
					$(this).parent('div').remove(); //Remove field html
					x--; //Decrement field counter
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				var maxField = 20;
				var addButton = $('.add_button1'); 
				var wrapper = $('.field_wrapper1'); 
				var fieldHTML = '<div class="row pb-3 pt-3"><div class="col-md-12 d-flex"><input type="hidden" id="tid" name="tid[]" value=""> <select id="start_time" name="start_time[]" class="form-control text-16 mr-3 w-50"><option value="">Select</option><?php for ($i=$start;$i<=$end;$i = $i + 30*60) { ?> <option value="<?php echo date('h:i A',$i); ?>" ><?php echo date('h:i A',$i); ?></option><?php } ?> </select><select id="end_time" name="end_time[]" class="form-control text-16 mr-3 w-50"><option value="">Select</option><?php for ($i=$start;$i<=$end;$i = $i + 30*60) { ?> <option value="<?php echo date('h:i A',$i); ?>" ><?php echo date('h:i A',$i); ?></option><?php } ?> </select><a href="javascript:void(0);" class="remove_button1 btn btn-danger text-13 btn-sm pt-3">{{trans('messages.experience.remove')}}</a></div></div>';
				var x = 1; 
				
				$(addButton).click(function(){
					if(x < maxField){ 
						x++; 
						$(wrapper).append(fieldHTML); 
					}
				});
				
				$(wrapper).on('click', '.remove_button1', function(e){
					e.preventDefault();
					$(this).parent('div').remove();
					var id = $(this).parent("div").find('#tid').val();
					    $.ajax({
							  type: 'post',
							  url: '{{url('/deletepackagetime/')}}',
							  data:{
								wishid:id,
							   '_token': '{{csrf_token()}}'
							  },
							  success: function (data)
							  {	
							  }
						});
					x--; 
				});
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function(){
				var maxField = 20;
				var addButton = $('.add_button2'); 
				var wrapper = $('.field_wrapper2'); 
				var fieldHTML = '<div class="row pb-3 pt-3"><div class="col-md-12 sv_family_package"><label>{{ trans("messages.experience.packages_name") }}</label><input type="text" id="title" name="title[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.packages_name") }}"><label>{{ trans("messages.experience.price") }}</label><input type="number" min="0" id="fprice" name="fprice[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.price") }}"><label>{{ trans("messages.experience.no_adults") }}</label><input type="number" min="0" id="adults" name="adults[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.no_adults") }}"><label>{{ trans("messages.experience.child") }}</label><input type="number" min="0" id="children" name="children[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.child") }}"><label>{{ trans("messages.experience.infants") }}</label><input type="text" id="infants" name="infants[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.infants") }}"><label>{{trans("messages.experience.full_details")}}</label><textarea id="full_details" name="full_details[]" class="form-control mr-3 mt-3" placeholder="{{trans("messages.experience.full_details_placeholder")}}"></textarea><label>{{trans("messages.experience.your_itinerary")}}</label><textarea id="itinerary" name="itinerary[]" class="form-control mr-3 mt-3"></textarea><label>{{trans("messages.experience.photo")}}</label><input type="file" id="file" name="file[]" class="form-control mr-3 mt-3" value=""><input type="hidden" id="fid" name="fid[]" value=""><a href="javascript:void(0);" class="remove_button2 btn btn-sm btn-danger text-13">Remove</a></div></div>';
				var x = 1; 
				
				$(addButton).click(function(){
					if(x < maxField){ 
						x++; 
						$(wrapper).append(fieldHTML); 
					}
				});
				
				$(wrapper).on('click', '.remove_button2', function(e){
					e.preventDefault();
					$(this).parent('div').remove();
					var id = $(this).parent("div").find('#fid').val();
					
					    $.ajax({
							  type: 'post',
							  url: '{{url('/deletepackage/')}}',
							  data:{
								wishid:id,
							   '_token': '{{csrf_token()}}'
							  },
							  success: function (data)
							  {	
							  }
						});
					x--; 
				});
			});
		</script>
	<script type="text/javascript">
		$(document).on('change', '.pricing_checkbox', function(){
			if(this.checked){
			var name = $(this).attr('data-rel');
			$('#'+name).show();
			}else{
			var name = $(this).attr('data-rel');
			$('#'+name).hide();
			$('#price-'+name).val(0);
			}
		});

		$(document).on('click', '#show_long_term', function(){
			$('#js-set-long-term-prices').hide();
			$('#long-term-div').show();
		});

		$(document).on('change', '#price-select-currency_code', function(){
			var currency = $(this).val();
			var dataURL = '{{url("currency-symbol")}}';
			//console.log(currency);
			$.ajax({
			url: dataURL,
			data: {
					"_token": "{{ csrf_token() }}",
					'currency': currency
				},
			type: 'post',
			dataType: 'json',
			success: function (result) {
				if(result.success == 1)
				$('.pay-currency').html(result.symbol);
			},
			error: function (request, error) {
				// This callback function will trigger on unsuccessful action
				console.log(error);
			}
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function () {
			$('#lis_pricing').validate({
				rules: {
					price: {
						required: true,
						number: true,
						min: 5
					},
					weekly_discount: {
						number: true,
						max: 99,
						min: 0
					},
					monthly_discount: {
						number: true,
						max: 99,
						min: 0
					}
				},
				errorPlacement: function (error, element) {
					console.log('dd', element.attr("name"))
					if (element.attr("name") == "price") {
						error.appendTo("#price-error");
					} else {
						error.insertAfter(element)
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
					price: {
						required:  "{{ __('messages.jquery_validation.required') }}",
						number: "{{ __('messages.jquery_validation.number') }}",
						min: "{{ __('messages.jquery_validation.min5') }}",
					},
					weekly_discount: {
						number: "{{ __('messages.jquery_validation.number') }}",
						max: "{{ __('messages.jquery_validation.max99') }}",
						min: "{{ __('messages.jquery_validation.min0') }}",
					},
					monthly_discount: {
						number: "{{ __('messages.jquery_validation.number') }}",
						max: "{{ __('messages.jquery_validation.max99') }}",
						min: "{{ __('messages.jquery_validation.min0') }}",
					}
				}
			});

		});
		
		
		$(document).on('change', '#exp_booking_type', function(){
			var id = this.value;
			if(id == "2")
			{	
				$('#time_div').show();
			}
			else
			{
				$('#time_div').hide();
			}	
		});
		
		$(document).on('change', '#exp_booking_type', function(){
			var id = this.value;
			if(id == "3")
			{	
				$('#family_div').show();
				$('.sv_price_div').hide();
			}
			else
			{
				$('#family_div').hide();
				$('.sv_price_div').show();
			}	
		});
	</script>
	@endpush