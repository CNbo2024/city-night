@extends('template')

@section('main')

    {!! NoCaptcha::renderJs() !!}


<div class="container-fluid user-login-section p-0">
    <div class="row">
        <div class="user-login">
            <div class="user-login-bg" style="background:url(<?php echo $user_login_img; ?>);"></div>
            
            <div class="user-login-content mb-5">
                <div class="user-login-form">
                    	@if(Session::has('message'))
        				<div class="row mt-3 justify-content-center">
        					<div class="p-2 text-center text-14 alert {{ Session::get('alert-class') }} alert-dismissable fade in opacity-1">
        						<a href="#"  class="close text-18" data-dismiss="alert" aria-label="close">&times;</a>
        						{{ Session::get('message') }}
        					</div>
        				</div>
			            @endif 
                    <h2 class="text-center font-weight-600 welcome-txt">{{trans('messages.login.welcome_back')}}</h2>
                    <div class="d-flex justify-content-center login-list mt-5">
                        <div><a class="act-active" href="{{URL::to('/')}}/login">{{trans('messages.login.login')}}</a></div>
                        <div><a href="{{URL::to('/')}}/signup">	{{trans('messages.sign_up.sign_up')}}</a></div>
                    </div>
                    <div class="row mt-5 justify-content-center" >
                        <div class="col-lg-7 col-md-12">
                            <form class="mt-3" id="login_form" method="post" action="{{url('authenticate')}}"  accept-charset='UTF-8'>  
    				        {{ csrf_field() }}
    				        <div class="form-group col-sm-12 p-0 mt-3">
    				            <label class="font-weight-600">{{trans('messages.login.email')}}</label>
            					<input type="email" class="form-control text-14" value="{{ old('email') }}" name="email" placeholder = "{{trans('messages.login.email')}}">
            					@if ($errors->has('email'))
            						<p class="error">{{ $errors->first('email') }}</p> 
            					@endif
            				</div>

            				<div class="form-group col-sm-12 p-0 mt-3">
            				    <label class="font-weight-600">{{trans('messages.login.password')}}</label>

            				    <div class="input-group">
            				    	<input type="password" class="form-control text-14" value="" name="password" placeholder = "{{trans('messages.login.password')}}">

            				    	<div class="input-group-append">
            				    		<button type="button" class="view-password btn btn-secondary p-2">
		            						<i class="fa fa-eye"></i>
		            					</button>
            				    	</div>
            				    </div>            					

            					@if ($errors->has('password')) 
            						<p class="error">{{ $errors->first('password') }}</p> 
            					@endif
            				</div>
							
							@if($enable_captcha=="yes")
							 <div class="col-sm-12 p-0 form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
								<div class="">
									{!! app('captcha')->display() !!}
									@if ($errors->has('g-recaptcha-response'))
										<span class="help-block text-danger">
											<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
										</span>
									@endif
								</div>
							</div>
							@endif
							
            				<div class="form-group col-sm-12 p-0 mt-3" >
            					<div class="d-flex justify-content-between">
            						<div class="m-3 text-14">
            							<input type="checkbox" class='remember_me' id="remember_me2" name="remember_me" value="1">
            							{{trans('messages.login.remember_me')}}
            						</div>
            						
            						<div class="m-3 text-14">
            							<a href="{{URL::to('/')}}/forgot_password" class="forgot-password text-right">{{trans('messages.login.forgot_pwd')}}</a>
            						</div>
            					</div>
            				</div>
            				<div class="form-group col-sm-12 p-0 mt-5" >
            					<button type='submit' id="btn" class="btn pb-3 pt-3  text-15 rounded-3 vbtn-success w-100 rounded"> <i class="spinner fa fa-spinner fa-spin d-none" ></i>
            							<span id="btn_next-text">{{trans('messages.login.login')}}</span>
            					</button>
				            </div>
							</form>
							
							<div id="sv_phone_div" class="hide">
							
							 <form class="mt-3" id="otp_form" method="post" action="{{url('sendotp')}}"  accept-charset='UTF-8'>  
									{{ csrf_field() }}
									
									<div class="form-group col-sm-12 p-0 mt-3" id="svpno">
										<label class="font-weight-600">{{trans('messages.users_profile.phone')}}</label>
										<input type="tel" class="form-control text-14" id="pno" name="pno" required value="">
									</div>
								    <input type="hidden" name="default_country" id="default_country" class="form-control">
									<input type="hidden" name="carrier_code1" id="carrier_code1" class="form-control">
									<input type="hidden" name="formatted_phone" id="formatted_phone" class="form-control">

									<div class="form-input svotp" style="display:none">
										<input type="text" class="form-control" id="otp" name="otp" placeholder="{{trans('messages.users_profile.otp')}}" >
										<i class="icofont-phone"></i>
									</div>
								  
									<button type='' id="svsendotp" class="btn pb-3 mt-3 pt-3 text-15 rounded-3 vbtn-success w-100 rounded"> <i class="spinner fa fa-spinner fa-spin d-none" ></i>
										<span id="btn_next-text">{{trans('messages.login.login')}} </span>
									</button>
							</form>
							
							</div>
							
							
							
    				        
    				        <div class="or-sec mb-5 mt-5">
                			    <span class="or-section">{{trans('messages.login.or')}}</span>
                			</div>
                			
                			@if($enable_facebook=="yes")
                			<a href="{{ isset($facebook_url) ? $facebook_url:URL::to('facebookLogin') }}">
                				<button class="btn fb_btn pt-3 pb-3 text-16 w-100">
                					<span><i class="fab fa-facebook-f mr-2 text-16"></i> {{trans('messages.sign_up.sign_up_with_facebook')}}</span>
                				</button>
    			            </a>
    			            @endif
    			            @if($enable_google=="yes")
                			<a href="{{URL::to('googleLogin')}}">
                				<button class="btn google_btn pt-3 pb-3 text-16 w-100 mt-3">
                				<span><i class="fab fa-google-plus-g  mr-2 text-16"></i>  {{trans('messages.sign_up.sign_up_with_google')}}</span>
                				</button>
                			</a>
                			@endif
							
							<?php if(isset($phoneSms['status'])) { if($phoneSms['status']=='1') { ?>
                			<button class="btn-success btn border-0 pt-3 pb-3 text-16 w-100 mt-3 phonebutt">
                				<span><i class="fa fa-mobile-alt  mr-2 text-16"></i> {{trans('messages.sign_up.sign_up_with_phone')}}</span>
                			</button>
							<?php } } ?>
							
							<button class="btn-success btn border-0 pt-3 pb-3 text-16 w-100 mt-3 emailbutt hide">
                				<span><i class="fa fa-envelope  mr-2 text-16"></i> {{trans('messages.sign_up.sign_up_with_email')}} </span>
                			</button>
							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@push('scripts')
<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>

<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
jQuery.validator.addMethod("laxEmail", function(value, element) {
	// allow any non-whitespace characters as the host part
	return this.optional( element ) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value );
}, "{{ __('messages.jquery_validation.email') }}" );

$(document).ready(function () {
	$('#login_form').validate({
		rules: {
			email: {
				required: true,
				maxlength: 255,
				laxEmail: true
			},

			password: {
				required: true
			}
		},
		submitHandler: function(form)
        {
 			$("#btn").on("click", function (e)
            {	
            	$("#btn").attr("disabled", true);
                e.preventDefault();
            });


            $(".spinner").removeClass('d-none');
            $("#btn_next-text").text("{{trans('messages.login.login')}}..");
            return true;
        },
		messages: {
			email: {
				required:  "{{ __('messages.jquery_validation.required') }}",
				maxlength: "{{ __('messages.jquery_validation.maxlength255') }}",
			},

			password: {
				required: "{{ __('messages.jquery_validation.required') }}",
			}
		}
	});
});
</script>

<script>
    $("#svsendotp").on('click', function(e) {
        		e.preventDefault();
        		var pno = $('#pno').val();
				var form = $('#otp_form')[0]; // You need to use standard javascript object here
				var formData = new FormData(form);
				//console.log(formData);
				if (pno == '') {
                    alert("Phone Number should not be empty.");
                    $("#pno").focus();
                }
                else{
            		$.ajax({
    	                type: 'POST',
						url: '{{url('/sendotp/')}}',
    	                data: formData,
    	                async: true,
    	                dataType: 'text',
    	                processData: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
    	                success: function(results) {
    	                    var data_array="";
    	                    var data_array=JSON.parse(results);
    	                     console.log(results);
    	                    if(data_array.success === false){
    	                       if(data_array.data.otp=="invalid")
    	                       {
    	                           alert("Invalid Phone Number");
    	                       }
    	                       else if(data_array.data.otp=="enterotp")
    	                       {
    	                           alert("Enter OTP");
    	                       }
    	                       else if(data_array.data.otp=="otpinvalid")
    	                       {
    	                           alert("Invalid OTP");
    	                       }
    	                       e.preventDefault();
    	                     }
    	                     if(data_array.success === true){
    	                        if(data_array.data.otp=="sent")
    	                        {
    	                            $('#svpno').hide(); 
    	                            $('.svotp').show();
    	                            $('#svsendotp').html('Submit');
    	                            
    	                        }
								 else if(data_array.data.otp=="login")
    	                        {
    	                            var mainurl = "{{url('/')}}";
    	                            window.location.href = mainurl+'/dashboard';
    	                        }
 	                           
    	                     }

    	                   }
    	                
    	            });
                }
			
		});		
	
	
	
	/*
		intlTelInput
		*/
		$(document).ready(function()
		{
			$("#pno").intlTelInput({
				separateDialCode: true,
				nationalMode: true,
				preferredCountries: ["<?php if($default_country=="") { echo "us"; } else { echo $default_country; } ?>"],
				autoPlaceholder: "polite",
				placeholderNumberType: "MOBILE",
				utilsScript: '{{ URL::to('/') }}/public/js/intl-tel-input-13.0.0/build/js/utils.js'
			});

			var countryData = $("#pno").intlTelInput("getSelectedCountryData");
			console.log(countryData);
			$('#default_country').val(countryData.iso2);
			$('#carrier_code1').val(countryData.dialCode);

			$("#pno").on("countrychange", function(e, countryData)
			{
				formattedPhone();
				// log(countryData);
				$('#default_country').val(countryData.iso2);
				$('#carrier_code1').val(countryData.dialCode);
				if ($.trim($(this).val()) !== '') {
					//Invalid Number Validation - Add
					if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
						$('#tel-error').addClass('error').html('Please enter a valid International Phone Number.').css("font-weight", "bold");
						hasPhoneError = true;
						$('#phone-error').hide();
					} else  {
						$('#tel-error').html('');

						$.ajax({
							method: "POST",
							url: "{{url('duplicate-phone-number-check')}}",
							dataType: "json",
							cache: false,
							data: {
								"_token": "{{ csrf_token() }}",
								'phone': $.trim($(this).val()),
								'carrier_code': $.trim(countryData.dialCode),
							}
						})
						.done(function(response)
						{
							if (response.status == true) {
								$('#tel-error').html('');
								$('#phone-error').show();

								$('#phone-error').addClass('error').html(response.fail).css("font-weight", "bold");
								hasPhoneError = true;
								enableDisableButton();
							} else if (response.status == false) {
								$('#tel-error').show();
								$('#phone-error').html('');

								hasPhoneError = false;
								enableDisableButton();
							}
						});
					}
				} else {
					$('#tel-error').html('');
					$('#phone-error').html('');
					hasPhoneError = false;
					enableDisableButton();
				}
			});
		});
		
		
		
		$(document).ready(function()
		{

			$('.view-password').click(function () {

				if ($('[name=password]').attr('type') == 'password') {
					$('[name=password]').attr('type', 'text');
				} else {
					$('[name=password]').attr('type', 'password');
				}
			});


			$("input[name=pno]").on('blur keyup', function(e)
			{
				formattedPhone();
				$('#btn').attr('disabled', false);
				$('#pno').html('').css("border-color","none");
				if ($.trim($(this).val()) !== '') {
					if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
						$('#tel-error').addClass('error').html('Please enter a valid International Phone Number.').css("font-weight", "bold");
						hasPhoneError = true;
						$('#btn').attr('disabled','disabled');
						$('#pno').css("border-color","#a94442");
						$('#phone-error').hide();
					} else {

						var phone = $(this).val().replace(/-|\s/g,""); //replaces 'whitespaces', 'hyphens'
						var phone = $(this).val().replace(/^0+/,"");  //replaces (leading zero - for BD phone number)
						var token = "{{csrf_token()}}";
						var pluginCarrierCode = $('#pno').intlTelInput('getSelectedCountryData').dialCode;
						$.ajax({
							url: "{{url('duplicate-phone-number-check')}}",
							method: "POST",
							dataType: "json",
							data: {
								'phone': phone,
								'carrier_code': pluginCarrierCode,
								'_token': "{{csrf_token()}}",
							}
						})
						.done(function(response)
						{
							if (response.status == true) {
								if (phone.length == 0) {
									$('#phone-error').html('');
								} else {
									$('#phone-error').addClass('error').html(response.fail).css("font-weight", "bold");
									hasPhoneError = true;
									enableDisableButton();
								}
							} else if (response.status == false) {
								$('#phone-error').html('');
								hasPhoneError = false;
								enableDisableButton();
							}
						});
						$('#tel-error').html('');
						$('#phone-error').show();
						hasPhoneError = false;
						enableDisableButton();
					}
				} else {
					$('#tel-error').html('');
					$('#phone-error').html('');
					hasPhoneError = false;
					enableDisableButton();
				}
			});
		});

		function formattedPhone()
		{
			if ($('#pno').val != '') {
				var p = $('#pno').intlTelInput("getNumber").replace(/-|\s/g,"");
				
				$("#formatted_phone").val(p);
			}
		}
		
		 $(".phonebutt").on('click', function(e) {
			 $('#login_form').hide();
			 $('.phonebutt').hide();
			  $('.emailbutt').show();
			 $('#sv_phone_div').show();

		 });

		$(".emailbutt").on('click', function(e) {
			 $('#login_form').show();
			 $('.phonebutt').show();
			  $('.emailbutt').hide();
			 $('#sv_phone_div').hide();

		 });
	
</script>

@endpush
