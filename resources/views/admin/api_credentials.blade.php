@php 
$form_data = [
    'page_title'=> 'Api Credentials Form',
    'page_subtitle'=> 'Api Credentials Page', 
    'form_name' => 'Api Credentials Form',
    'form_id' => 'api_credentials',
    'action' => URL::to('/').'/admin/settings/api-informations',
    'form_type' => 'file',
    'fields' => [
        ['type' => 'text', 'class' => '', 'label' => 'Facebook Client ID', 'name' => 'facebook_client_id', 'value' => $facebook['client_id']],
        ['type' => 'text', 'class' => '', 'label' => 'Facebook Client Secret', 'name' => "facebook_client_secret", 'value' => $facebook['client_secret']],
        ['type' => 'text', 'class' => '', 'label' => 'Google Client ID', 'name' => "google_client_id", 'value' => $google['client_id']],
        ['type' => 'text', 'class' => '', 'label' => 'Google Client Secret', 'name' => 'google_client_secret', 'value' => $google['client_secret']],
        ['type' => 'text', 'class' => '', 'label' => 'Google Map Browser Key', 'name' => 'google_map_key', 'value' => $google_map['key']],
        ['type' => 'text', 'class' => '', 'label' => 'Google Map Geocode Key(Search)', 'name' => 'google_map_search_key', 'value' => $google_map['google_map_search_key']],

        ['type' => 'text', 'class' => '', 'label' => 'Google recaptcha Site Key', 'name' => 'recaptcha_site_key', 'value' => $google['recaptcha_site_key']],
        ['type' => 'text', 'class' => '', 'label' => 'Google recaptcha Secret Key', 'name' => 'recaptcha_secret_key', 'value' => $google['recaptcha_secret_key' ], 'hint' =>'Visit ReCAPTCHA admin panel and create a project. Select version v2. (https://www.google.com/recaptcha/admin)'],

    ]
  ];
@endphp
@include("admin.common.form.setting", $form_data)

<script type="text/javascript">
   $(document).ready(function () {

            $('#api_credentials').validate({
                rules: {
                    facebook_client_id: {
                        required: true
                    },
                    facebook_client_secret: {
                        required: true
                    },
                    google_client_id: {
                        required: true
                    },
                    google_client_secret: {
                        required: true
                    },
                    google_map_key: {
                        required: true
                    }
                }
            });

        });
</script>