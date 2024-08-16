@extends('admin.template')
@section('main')
  <div class="content-wrapper sv_content_wrapper">
         <!-- Main content -->
  <section class="content-header">
          <h1>
          Pricing
        </h1>
       
  </section>
<section class="content">
<div class="row">
        <div class="col-md-3">
          @include('admin.common.experience_bar')
        </div>
<?php
							$start = strtotime('00:00');
							$end   = strtotime('23:30');
						?>
   <div class="col-md-9">
    <form id="listing_pricing" method="post" action="{{url('admin/experience/'.$result->id.'/'.$step)}}" class='signup-form login-form' accept-charset='UTF-8' enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="box box-info">
      <div class="box-body mt-0">
        <div class="row">
          <div class="col-md-12">
           <h4>{{trans('messages.listing_price.base_price')}}</h4>
          </div>
        </div>
        
        
        <div class="row">
          <div class="col-md-4 sv_price_div" <?php if($result->exp_booking_type == "3") { ?> style="display:none" <?php } else { ?> style="display:block"  <?php } ?>>
            <label for="listing_price_native" class="label-large">{{trans('messages.listing_price.night_price')}} <span class="text-danger">*</span></label>
            <div class="input-addon">
              <span class="input-prefix pay-currency">{!! $result->property_price->currency->org_symbol !!}</span>
              <input type="text" data-suggested="" id="price-night" value="{{ ($result->property_price->original_price == 0) ? '' : $result->property_price->original_price }}" name="price" class="money-input form-control">
            </div>
            <span class="text-danger">{{ $errors->first('price') }}</span>
          </div>
          <div class="col-md-4">
            <label class="label-large">{{trans('messages.listing_price.currency')}}</label>
            <select id='price-select-currency_code' name="currency_code" class='form-control'>
              @foreach($currency as $key => $value)
                <option value="{{$key}}" {{$result->property_price->currency_code == $key?'selected':''}}>{{$value}}</option>
              @endforeach
            </select>
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

									
							
									<div class="col-md-12 mt-4 pb-5  pl-sm-0 pr-sm-0 mob-pd-0" id="family_div" <?php if($result->exp_booking_type == "3") { ?> style="display:block" <?php } else { ?> style="display:none" <?php } ?>>
                                    		
												<div class="field_wrapper2">
													<?php foreach($family as $family) { ?>
													
														<div class="col-md-12 sv_family_package" style="border-bottom:1px solid #ddd;padding:5px 0;">
														 
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
                                                            
                                                            <label>{{trans("messages.experience.your_itinerary")}}</label>
															<textarea id="itinerary" name="itinerary[]" class="form-control mr-3 mt-3">{{ $family->itinerary }}</textarea>
                                                            
                                                            <label>{{trans("messages.experience.photo")}}</label>
															<input type="file" id="file" name="file[]" class="form-control mr-3 mt-3" value="">
															@if($family->file!="")
															    <img src="{{url('public/images/experience/'.$result->id.'/'.$family->file)}}" width="100px" height:"100px">
															@endif
															
														    <a href="javascript:void(0);" class="remove_button2 btn btn-sm text-13 btn-danger">{{trans('messages.experience.remove')}}</a>
														  
														</div>
														
													
													<?php } ?>
												
													<div class="col-md-12" style="margin:15px 0;">
													<a href="javascript:void(0);" class="add_button2 btn-sm btn-success" title="Add field">Add More</a>
													</div>

												</div>
										</div>
									
									
									
                                    	<div class="col-md-12 mt-4 pb-5  pl-sm-0 pr-sm-0 mob-pd-0" id="time_div" <?php if($result->exp_booking_type == "2") { ?> style="display:block" <?php } else { ?> style="display:none" <?php } ?>>
                                    			<div class="form-group col-md-12 pt-3 mt-sm-0 ">
                                    				<h4 class="text-18 font-weight-700 pl-3">{{trans('messages.experience.time')}}</h4>
                                    			</div>
												<div class="field_wrapper1">
													<?php foreach($time as $time) { ?>
													<div class="pb-3 pt-3" >
														<div class="col-md-12 d-flex svtime">
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
															<a href="javascript:void(0);" class="remove_button1 btn-sm btn-danger">Remove</a>
														</div>
														
													</div>
													<?php } ?>
													<a href="javascript:void(0);" class="add_button1 btn-sm btn-success" title="Add field">Add More</a>

												</div>
										</div>
									
								<div style="clear:both;"></div>
								
							
								
  
        </div>

      <br>
        <div class="row">
          <div class="col-md-6 col-xs-6 text-left">
            <a data-prevent-default="" href="{{ url('admin/experience/'.$result->id.'/photos') }}" class="btn btn-large btn-default">{{trans('messages.listing_description.back')}}</a>
          </div>
          <div class="col-md-6 col-xs-6 text-right">
            <button type="submit" class="btn btn-large btn-primary next-section-button">
              {{trans('messages.listing_basic.next')}}
            </button>
          </div>
        </div>
    </div>
    </div>
    </form>
    </div>
    </div>
    </section>
  </div>

@push('scripts')
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
        data: {'currency': currency},
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
  

		<script type="text/javascript">
			$(document).ready(function(){
				var maxField = 20;
				var addButton = $('.add_button1'); 
				var wrapper = $('.field_wrapper1'); 
				var fieldHTML = '<div class="pb-3 pt-3"><div class="col-md-12 d-flex svtime"><input type="hidden" id="tid" name="tid[]"> <select id="start_time" name="start_time[]" class="form-control text-16 mr-3 w-50"><option value="">Select</option><?php for ($i=$start;$i<=$end;$i = $i + 30*60) { ?> <option value="<?php echo date('h:i A',$i); ?>" ><?php echo date('h:i A',$i); ?></option><?php } ?> </select><select id="end_time" name="end_time[]" class="form-control text-16 mr-3 w-50"><option value="">Select</option><?php for ($i=$start;$i<=$end;$i = $i + 30*60) { ?> <option value="<?php echo date('h:i A',$i); ?>" ><?php echo date('h:i A',$i); ?></option><?php } ?> </select><a href="javascript:void(0);" class="remove_button1 btn-sm btn-danger">Remove</a></div></div>';
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
				var fieldHTML = '<div class="row pb-3 pt-3"><div class="col-md-12 sv_family_package"><label>{{ trans("messages.experience.packages_name") }}</label><input type="text" id="title" name="title[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.packages_name") }}"><label>{{ trans("messages.experience.price") }}</label><input type="number" min="0" id="fprice" name="fprice[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.price") }}"><label>{{ trans("messages.experience.no_adults") }}</label><input type="number" min="0" id="adults" name="adults[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.no_adults") }}"><label>{{ trans("messages.experience.child") }}</label><input type="number" min="0" id="children" name="children[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.child") }}"><label>{{ trans("messages.experience.infants") }}</label><input type="text" id="infants" name="infants[]" value="" class="form-control mr-3 mt-3" placeholder="{{ trans("messages.experience.infants") }}"><label>{{trans("messages.experience.your_itinerary")}}</label><textarea id="itinerary" name="itinerary[]" class="form-control mr-3 mt-3"></textarea><label>{{trans("messages.experience.photo")}}</label><input type="file" id="file" name="file[]" class="form-control mr-3 mt-3" value=""><input type="hidden" id="fid" name="fid[]" value=""><a href="javascript:void(0);" class="remove_button2 btn btn-sm btn-danger text-13">Remove</a></div></div>';
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

@endpush
@stop

@section('validate_script')
<script type="text/javascript">
   $(document).ready(function () {

            $('#listing_pricing').validate({
                rules: {
                    price: {
                        required: true
                    }
                }
            });

        });
</script>
@endsection