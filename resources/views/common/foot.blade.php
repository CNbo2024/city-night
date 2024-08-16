		<!-- New Js start-->
		<script src="{{asset('public/js/jquery-2.2.4.min.js')}}"></script>
		<script src="{{asset('public/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{asset('public/js/main.js')}}"></script>

		  {!! @$head_code !!}

		<!-- New Js End -->
		<!-- Needed Js from Old Version Start-->
		<script type="text/javascript">
			var APP_URL = "{{ url('/') }}";
			var USER_ID = "{{ isset(Auth::user()->id)  ? Auth::user()->id : ''  }}";
			var sessionDate      = '{!! Session::get('date_format_type') !!}';

		$(".currency_footer").on('click', function() {
			var currency = $(this).data('curr');
				$.ajax({
					type: "POST",
					url: APP_URL + "/set_session",
					data: {
						"_token": "{{ csrf_token() }}",
						'currency': currency
						},
					success: function(msg) {
						location.reload()
					},
			});
		});

		$(".language_footer").on('click', function() {
			var language = $(this).data('lang');
			$.ajax({
				type: "POST",
				url: APP_URL + "/set_session",
				data: {
						"_token": "{{ csrf_token() }}",
						'language': language
					},
				success: function(msg) {
					location.reload()
				},
			});
		});

		</script>
		
		<link rel="stylesheet" type="text/css" href="{{ asset('public/js/intl-tel-input-13.0.0/build/css/intlTelInput.min.css')}}">

		<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/js/intl-tel-input-13.0.0/build/js/intlTelInput.js')}}"></script>
	<script type="text/javascript" src="{{ asset('public/js/isValidPhoneNumber.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
		/* $('select').on('change', function() {
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
			}); */

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
		<?php if(!request()->is('signup')) { ?>

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
			$("input[name=phone]").on('blur keyup', function(e)
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
			});
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
	<?php } ?>
		
		<!-- Needed Js from Old Version End -->
		@stack('scripts')
	</body>
</html>