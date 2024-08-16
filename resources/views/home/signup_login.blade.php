@extends('template')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('public/js/intl-tel-input-13.0.0/build/css/intlTelInput.min.css')}}">
@endpush
@push('scripts')
<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>
@endpush
@section('main')

<div class="container-fluid user-login-section p-0">
    <div class="row">
        <div class="user-login">
            <div class="user-login-bg" style="background:url(<?php echo $user_login_img; ?>);"></div>
            
            <div class="user-login-content">
                <div class="user-login-form">
                     <h2 class="text-center font-weight-600 welcome-txt">{{ trans('messages.sign_up.signup_today_for_free') }} </h2>
                    <div class="d-flex justify-content-center login-list mt-5">
                        <div><a href="{{URL::to('/')}}/login">{{trans('messages.login.login')}}</a></div>
                        <div><a class="act-active" href="{{URL::to('/')}}/signup">{{trans('messages.sign_up.sign_up')}}</a></div>
                    </div>
                    <div class="row mt-5 justify-content-center mb-5" >
                        <div class="col-lg-7 col-md-12">
                			<form id="signup_form" name="signup_form" method="post" action="{{url('create')}}" class='signup-form login-form' accept-charset='UTF-8' onsubmit="return ageValidate();">    
					            {{ csrf_field() }}
					            <input type="hidden" name='email_signup' id='form'>
        						<input type="hidden" name="default_country" id="default_country" class="form-control">
        						<input type="hidden" name="carrier_code" id="carrier_code" class="form-control">
        						<input type="hidden" name="formatted_phone" id="formatted_phone" class="form-control">
        						<div class="row">
    					             <div class="form-group col-sm-6 p-0 mt-3">
        				                <label class="font-weight-600">{{ trans('messages.sign_up.first_name') }}</label>
        				                <input type="text" class='form-control text-14 p-2' value="{{ old('first_name') }}" name='first_name' id='first_name' placeholder='{{ trans('messages.sign_up.first_name') }}'>
        				                @if ($errors->has('first_name')) <p class="error-tag">{{ $errors->first('first_name') }}</p> @endif
        				            </div>
        				            <div class="form-group col-sm-6 pl-1 pr-0 mt-3">
        				                <label class="font-weight-600">{{ trans('messages.sign_up.last_name') }}</label>
        				                <input type="text" class='form-control text-14 p-2' value="{{ old('last_name') }}" name='last_name' id='last_name' placeholder='{{ trans('messages.sign_up.last_name') }}'>
        				                @if ( $errors->has('last_name') ) <p class="error-tag">{{ $errors->first('last_name') }}</p> @endif
    				                </div>
				                </div>
				                <div class="form-group col-sm-12 p-0 mt-3">
				                     <label class="font-weight-600">{{ trans('messages.login.email') }}</label>
    								<input type="text" class='form-control text-14 p-2' value="{{old('email')}}" name='email' id='email' placeholder='{{ trans('messages.login.email') }}'>
    								@if ($errors->has('email')) 
    									<p class="error-tag">
    									{{ $errors->first('email') }}
    									</p> 
    								@endif
    								<div id="emailError"></div>
						        </div>
						        <div class="form-group col-sm-12 p-0 mt-3">
						             <label class="font-weight-600">{{ trans('messages.users_profile.phone') }}</label>
    								<input type="tel" class="form-control text-14 p-2" id="phone" name="phone" placeholder="111-111-1111">
						        </div>
						        <div class="form-group col-sm-12 p-0 mt-3">
						             <label class="font-weight-600">{{ trans('messages.login.password') }}</label>
    								 <input type="password" class='form-control text-14 p-2' name='password' id='password' placeholder='{{ trans('messages.login.password') }}'>
									@if ( $errors->has('password') ) <p class="error-tag">{{ $errors->first('password') }}</p> @endif
						        </div>
						        
						        
						        	@if($enable_captcha=="yes")
								{!! NoCaptcha::renderJs() !!}

								<div class="form-group col-md-12 p-0 mt-4 {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
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
								
						        <div class="form-group col-sm-12 p-0 mt-5" >
                					<button type='submit' id="btn" class="btn pb-3 pt-3 text-15 rounded-3 vbtn-success w-100 ml-0 mr-0 mb-3"> <i class="spinner fa fa-spinner fa-spin d-none" ></i>
							            <span id="btn_next-text">{{ trans('messages.sign_up.sign_up') }}</span>
						            </button>
				                </div>
				            </form>
				            <div class="or-sec mb-5 mt-2">
                			    <span class="or-section">{{trans('messages.login.or')}}</span>
                			</div>
                			@if($enable_facebook=="yes")
                			<a href="{{ isset( $facebook_url ) ? $facebook_url:URL::to('facebookLogin') }}">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
	<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/js/intl-tel-input-13.0.0/build/js/intlTelInput.js')}}"></script>
	<script type="text/javascript" src="{{ asset('public/js/isValidPhoneNumber.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
		$('select').on('change', function() {
			var dobError = ''; 
			var day = document.getElementById("user_birthday_day").value;
			var month = document.getElementById("user_birthday_month").value;
			var y = document.getElementById("user_birthday_year").value;
			var year = parseInt(y);
			var year2 = signup_form.birthday_year;
			var age = 18;

			var setDate = new Date(year + age, month - 1, day);
			var currdate = new Date();
			if (day == '' || month == '' || y == '') {
				$('#dobError').html('<label class="text-danger">'+"{{ __('messages.jquery_validation.required') }}"+'</label>');
				year2.focus();
				return false;
			}
			else if (setDate > currdate) {
				$('#dobError').html('<label class="text-danger">'+"{{ __('messages.jquery_validation.age_greater_than_18') }}"+'</label>');
					year2.focus();
					return false;
				}
				else
				{
					$('#dobError').html('<span class="text-danger"></span>');
					return true;
				}
			});

		function ageValidate()
		{
			var dobError = ''; 
			var day = document.getElementById("user_birthday_month").value;
			var month = document.getElementById("user_birthday_day").value;
			var y = document.getElementById("user_birthday_year").value;
			var year = parseInt(y);
			var year2 = signup_form.birthday_year;
			var age = 18;

			var setDate = new Date(year + age, month - 1, day);
			var currdate = new Date();
			if (day == '' || month == '' || y == '') {
				$('#dobError').html('<label class="text-danger">'+"{{ __('messages.jquery_validation.required') }}"+'</label>');
				year2.focus();
				return false;
			}
			else if (setDate > currdate) {
				$('#dobError').html('<label class="text-danger">'+"{{ __('messages.jquery_validation.age_greater_than_18') }}"+'</label>');
				year2.focus();
				return false;
				}
				else
				{
				$('#dobError').html('<span class="text-danger"></span>');
				return true;
				}
			}

			$('#signup_form').validate({
				rules: {
					first_name: {
						required: true,
						maxlength: 255
					},
					last_name: {
						required: true,
						maxlength: 255
					},
					email: {
						required: true,
						maxlength: 255,
						laxEmail: true
					},
					password: {
						required: true,
						minlength: 6
					},
					birthday_month: {
						required: true
					},
					birthday_day: {
						required: true
					},
					birthday_year: {
						required: true,
						minAge: 18
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
	                $("#btn_next-text").text("{{trans('messages.sign_up.sign_up')}}..");
	                return true;
	            },

	            errorPlacement: function (error, element) {				
					$('#user_birthday_month-error').addClass('d-none');
					$('#user_birthday_day-error').addClass('d-none');
					error.insertAfter(element);
					$('#user_birthday_year-error').addClass('d-none');

				},

				messages: {
				first_name: {
					required:  "{{ __('messages.jquery_validation.required') }}",
					maxlength: "{{ __('messages.jquery_validation.maxlength255') }}",
				},
				last_name: {
					required:  "{{ __('messages.jquery_validation.required') }}",
					maxlength: "{{ __('messages.jquery_validation.maxlength255') }}",
				},
				email: {
					required:  "{{ __('messages.jquery_validation.required') }}",
					maxlength: "{{ __('messages.jquery_validation.maxlength255') }}",
				},
				password: {
					required:  "{{ __('messages.jquery_validation.required') }}",
					minlength: "{{ __('messages.jquery_validation.minlength6') }}",
				},
				birthday_day: {
					required:  "{{ __('messages.jquery_validation.required') }}",
				},
				birthday_month: {
					required:  "{{ __('messages.jquery_validation.required') }}",
				},
				birthday_year: {
					required:  "{{ __('messages.jquery_validation.required') }}",
				}
				}
			});

			jQuery.validator.addMethod("laxEmail", function(value, element) {
				return this.optional( element ) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value );
			}, "{{ __('messages.jquery_validation.email') }}" );


			$(document).on('blur keyup', '#email', function() {
				var emailError = '';
				var email      = $('#email').val();
				var _token     = $('input[name="_token"]').val();
				$('.error-tag').html('').hide();
				if(email != '') {
				$.ajax({
					url:"{{ route('checkUser.check') }}",
					method:"POST",
					data:{
							email:email, 
							"_token": "{{ csrf_token() }}",
							},
					success:function(result)
					{
						if (result == 'not_unique') {
							$('#emailError').html('<label class="text-danger">'+"{{ __('messages.jquery_validation.email_existed') }}"+'</label>');
							$('#email').addClass('has-error');
							$('#btn').attr('disabled', 'disabled');
						} else {
							$('#email').removeClass('has-error');
							$('#emailError').html('');
							$('#btn').attr('disabled', false);
						}
					}
				})
				} else {
					$('#emailError').html('');
				}
				
		});

	</script>

	<script type="text/javascript">
		var hasPhoneError = false;
		var hasEmailError = false;

		//jquery validation
		$.validator.setDefaults({
			highlight: function(element) {
				$(element).parent('div').addClass('has-error');
			},
			unhighlight: function(element) {
				$(element).parent('div').removeClass('has-error');
			},
			errorPlacement: function (error, element) {
					$('.error-tag').html('').hide();
					$('#emailError').html('').hide();
					error.insertAfter(element);
			}
		});

		/*
		intlTelInput
		*/
		$(document).ready(function()
		{
			$("#phone").intlTelInput({
				separateDialCode: true,
				nationalMode: true,
				preferredCountries: ["<?php if($default_country=="") { echo "us"; } else { echo $default_country; } ?>"],
				autoPlaceholder: "polite",
				placeholderNumberType: "MOBILE",
				utilsScript: '{{ URL::to('/') }}/public/js/intl-tel-input-13.0.0/build/js/utils.js'
			});

			var countryData = $("#phone").intlTelInput("getSelectedCountryData");
			$('#default_country').val(countryData.iso2);
			$('#carrier_code').val(countryData.dialCode);

			$("#phone").on("countrychange", function(e, countryData)
			{
				formattedPhone();
				// log(countryData);
				$('#default_country').val(countryData.iso2);
				$('#carrier_code').val(countryData.dialCode);
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
			/*$("input[name=phone]").on('blur keyup', function(e)
			{
				formattedPhone();
				$('#btn').attr('disabled', false);
				$('#phone').html('').css("border-color","none");
				if ($.trim($(this).val()) !== '') {
					if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
						$('#tel-error').addClass('error').html('Please enter a valid International Phone Number.').css("font-weight", "bold");
						hasPhoneError = true;
						$('#btn').attr('disabled','disabled');
						$('#phone').css("border-color","#a94442");
						$('#phone-error').hide();
					} else {

						var phone = $(this).val().replace(/-|\s/g,""); //replaces 'whitespaces', 'hyphens'
						var phone = $(this).val().replace(/^0+/,"");  //replaces (leading zero - for BD phone number)
						var token = "{{csrf_token()}}";
						var pluginCarrierCode = $('#phone').intlTelInput('getSelectedCountryData').dialCode;
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
			});*/
		});

		function formattedPhone()
		{
			if ($('#phone').val != '') {
				var p = $('#phone').intlTelInput("getNumber").replace(/-|\s/g,"");
				$("#formatted_phone").val(p);
			}
		}
		function enableDisableButton() {
			if (!hasPhoneError) {
				$('form').find("button[type='submit']").prop('disabled', false);
			} else {
				$('form').find("button[type='submit']").prop('disabled', true);
			}
		}

		$.validator.addMethod("minAge", function(value, element, min) {
		    var today = new Date();
		    var birthDate = new Date(value);
		    var age = today.getFullYear() - birthDate.getFullYear();
		 
		    if (age > min+1) { return true; }
		 
		    var m = today.getMonth() - birthDate.getMonth();
		 
		    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) { age--; }
		 
		    return age >= min;
		}, "You are not old enough!");


	</script>
@endpush