@php 
$form_data = [
		'page_title'=> 'Fees Setting Form',
		'page_subtitle'=> 'Fees Setting Page', 
		'form_name' => 'Fees Setting Form',
		'form_id' => 'fees_setting',
		'action' => URL::to('/').'/admin/settings/fees',
		'fields' => [
			
      		['type' => 'text', 'class' => '', 'label' => 'Guest service charge (%)', 'name' => "guest_service_charge", 'value' => $result['guest_service_charge'], 'hint' => 'service charge of guest for booking'],

            ['type' => 'text', 'class' => '', 'label' => 'I.V.A Tax (%)', 'name' => "iva_tax", 'value' => $result['iva_tax'], 'hint' => 'I.V.A Tax of guest for booking'],

            ['type' => 'text', 'class' => '', 'label' => 'Accomadation Tax (%)', 'name' => "accomodation_tax", 'value' => $result['accomodation_tax'], 'hint' => 'accomadation Tax of guest for booking'],

      		['type' => 'text', 'class' => '', 'label' => 'Host service charge (%)', 'name' => "host_service_charge", 'value' => $result['host_service_charge'], 'hint' => 'Host Service Charge'],

			['type' => 'text', 'class' => 'validate_field', 'label' => 'Host Cancellation Fees More than 7 days before check-in', 'name' => 'more_then_seven', 'value' => @$result['more_then_seven'], 'hint' => 'If host cancel booking more then 7 day before arrival this fee will apply.'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Host Cancellation Fees Less than 7 days before check-in', 'name' => "less_then_seven", 'value' => @$result['less_then_seven'], 'hint' => 'If host cancel booking less then 7 day before arrival this fee will apply.'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Host Free Cancelation Limit', 'name' => "cancel_limit", 'value' => @$result['cancel_limit'], 'hint' => 'Free Cancelation Limit'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Host Penalty', 'name' => "host_penalty", 'value' => @$result['host_penalty'], 'hint' => 'If host is not respond (Accept / Decline) request Book.'],


      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Guest Cancellation Policy', 'name' => "flexible", 'value' => 'Flexible', 'hint' => 'flexible', 'readonly'=>'readonly'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Before days', 'name' => "flexible_day_before", 'value' => @$result['flexible_day_before'], 'hint' => 'Flexible: Full refund before days prior to arrival except service fees'],

      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Before the day Prior to arrival check-in(%)', 'name' => "flexible_day_before_percentage", 'value' => @$result['flexible_day_before_percentage'], 'hint' => 'Refund Percentage'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'After the day Prior to arrival check-in(%)', 'name' => "flexible_day_after_percentage", 'value' => @$result['flexible_day_after_percentage'], 'hint' => ''],
      		
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Guest Cancellation Policy', 'name' => "moderate", 'value' => 'Moderate', 'hint' => 'Moderate', 'readonly'=>'readonly'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Before days', 'name' => "moderate_day_before", 'value' => @$result['moderate_day_before'], 'hint' => 'Moderate: Full refund before days prior to arrival except service fees'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Before the day Prior to arrival check-in(%)', 'name' => "moderate_day_before_percentage", 'value' => @$result['moderate_day_before_percentage'], 'hint' => 'Refund Percentage'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'After the day Prior to arrival check-in(%)', 'name' => "moderate_day_after_percentage", 'value' => @$result['moderate_day_after_percentage'], 'hint' => ''],


            ['type' => 'text', 'class' => 'validate_field', 'label' => 'Guest Cancellation Policy', 'name' => "strict", 'value' => 'Strict', 'hint' => 'Strict', 'readonly'=>'readonly'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Before days', 'name' => "strict_day_before", 'value' => @$result['strict_day_before'], 'hint' => 'Strict: Full refund before days prior to arrival except service fees'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Before the day Prior to arrival check-in(%)', 'name' => "strict_day_before_percentage", 'value' => @$result['strict_day_before_percentage'], 'hint' => 'Refund Percentage'],
      		['type' => 'text', 'class' => 'validate_field', 'label' => 'After the day Prior to arrival check-in(%)', 'name' => "strict_day_after_percentage", 'value' => @$result['strict_day_after_percentage'], 'hint' => ''],

		]
	];
@endphp
@include("admin.common.form.setting", $form_data)
<script type="text/javascript">
   $(document).ready(function () {

            $('#fees_setting').validate({
                rules: {
                    guest_service_charge: {
                        required: true
                    }
                }
            });

        });
</script>