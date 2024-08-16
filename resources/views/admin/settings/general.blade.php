@php 
if($result['logo']==null)
{
    $logoPath = "public/front/images/default-error-image.png";
}
else {
    $logoPath = 'public/front/images/logos/'.$result['logo'];
}

if($result['light_logo']==null)
{
    $light_logoPath = "public/front/images/default-error-image.png";
}
else {
    $light_logoPath = 'public/front/images/logos/'.$result['light_logo'];
}
if($result['favicon']==null)
{
    $faviconPath = "public/front/images/default-error-image.png";
}
else {
    $faviconPath = 'public/front/images/logos/'.$result['favicon'];
}



if($result['user_login_img']==null)
{
    $user_login_img_Path = "public/front/images/login-bg.jpg";
}
else {
    $user_login_img_Path = 'public/front/images/logos/'.$result['user_login_img'];
}

if($result['admin_login_img']==null)
{
    $admin_login_img_Path = "public/front/images/admin-login-bg.png";
}
else {
    $admin_login_img_Path = 'public/front/images/logos/'.$result['admin_login_img'];
}


if($result['list_your_space']==null)
{
    $list_your_space = "public/images/step1.jpg";
}
else {
    $list_your_space = 'public/front/images/logos/'.$result['list_your_space'];
}

if($result['description_img']==null)
{
    $description_img = "public/images/step2.jpg";
}
else {
    $description_img = 'public/front/images/logos/'.$result['description_img'];
}

if($result['hosting_third_img']==null)
{
    $hosting_third_img = "public/images/step3.jpg";
}
else {
    $hosting_third_img = 'public/front/images/logos/'.$result['hosting_third_img'];
}
if($result['hosting_fourth_img']==null)
{
    $hosting_fourth_img = "public/images/step4.jpg";
}
else {
    $hosting_fourth_img = 'public/front/images/logos/'.$result['hosting_fourth_img'];
}
if($result['hosting_fifth_img']==null)
{
    $hosting_fifth_img = "public/images/step5.jpg";
}
else {
    $hosting_fifth_img = 'public/front/images/logos/'.$result['hosting_fifth_img'];
}
if($result['hosting_sixth_img']==null)
{
    $hosting_sixth_img = "public/images/step6.jpg";
}
else {
    $hosting_sixth_img = 'public/front/images/logos/'.$result['hosting_sixth_img'];
}
if($result['hosting_seventh_img']==null)
{
    $hosting_seventh_img = "public/images/step7.jpg";
}
else {
    $hosting_seventh_img = 'public/front/images/logos/'.$result['hosting_seventh_img'];
}
if($result['hosting_eighth_img']==null)
{
    $hosting_eighth_img = "public/images/step8.jpg";
}
else {
    $hosting_eighth_img = 'public/front/images/logos/'.$result['hosting_eighth_img'];
}
if($result['hosting_ninth_img']==null)
{
    $hosting_ninth_img = "public/images/step9.jpg";
}
else {
    $hosting_ninth_img = 'public/front/images/logos/'.$result['hosting_ninth_img'];
}
if($result['try_hosting_img']==null)
{
    $try_hosting_img = "public/images/try-hosting.jpg";
}
else {
    $try_hosting_img = 'public/front/images/logos/'.$result['try_hosting_img'];
}


if($result['experience_first_img']==null)
{
    $experience_first_img = "public/images/step1.jpg";
}
else {
    $experience_first_img = 'public/front/images/logos/'.$result['experience_first_img'];
}

if($result['experience_second_img']==null)
{
    $experience_second_img = "public/images/step2.jpg";
}
else {
    $experience_second_img = 'public/front/images/logos/'.$result['experience_second_img'];
}

if($result['experience_third_img']==null)
{
    $experience_third_img = "public/images/step3.jpg";
}
else {
    $experience_third_img = 'public/front/images/logos/'.$result['experience_third_img'];
}
if($result['experience_fourth_img']==null)
{
    $experience_fourth_img = "public/images/step4.jpg";
}
else {
    $experience_fourth_img = 'public/front/images/logos/'.$result['experience_fourth_img'];
}
if($result['experience_fifth_img']==null)
{
    $experience_fifth_img = "public/images/step5.jpg";
}
else {
    $experience_fifth_img = 'public/front/images/logos/'.$result['experience_fifth_img'];
}
if($result['experience_sixth_img']==null)
{
    $experience_sixth_img = "public/images/step6.jpg";
}
else {
    $experience_sixth_img = 'public/front/images/logos/'.$result['experience_sixth_img'];
}
if($result['experience_seventh_img']==null)
{
    $experience_seventh_img = "public/images/step7.jpg";
}
else {
    $experience_seventh_img = 'public/front/images/logos/'.$result['experience_seventh_img'];
}
if($result['experience_eighth_img']==null)
{
    $experience_eighth_img = "public/images/step8.jpg";
}
else {
    $experience_eighth_img = 'public/front/images/logos/'.$result['experience_eighth_img'];
}
if($result['experience_ninth_img']==null)
{
    $experience_ninth_img= "public/images/step9.jpg";
}
else {
    $experience_ninth_img = 'public/front/images/logos/'.$result['experience_ninth_img'];
}


$form_data = [
		'page_title'=> 'General Setting Form',
		'page_subtitle'=> 'General Setting Page', 
		'form_name' => 'General Setting Form',
		'form_id' => 'general_form',
		'action' => URL::to('/').'/admin/settings',
		'form_type' => 'file',
		'fields' => [ 
			['type' => 'text', 'class' => '', 'label' => 'Name', 'name' => 'name', 'value' => $result['name']],
			['type' => 'text', 'class' => '', 'label' => 'SEO Title', 'name' => 'seo_title', 'value' => $meta['title']],
			['type' => 'textarea', 'class' => '', 'label' => 'Meta Description', 'name' => 'meta_description', 'value' => $meta['description']],
			['type' => 'textarea', 'class' => '', 'label' => 'Keywords', 'name' => 'keywords', 'value' => $meta['keywords']],
			
            ['type' => 'file', 'class' => '', 'label' => 'Dark Logo', 'name' => "photos[logo]", 'value' => '', 'image' => url($logoPath), 'custom_span' =>$result['logo'], 'custom_company_logo' => $result['logo']] ,

			['type' => 'file', 'class' => '', 'label' => 'Light Logo', 'name' => "photos[light_logo]", 'value' => '', 'image' => url($light_logoPath), 'custom_span' =>$result['light_logo'], 'custom_company_logo' => $result['light_logo']] ,

			['type' => 'file', 'class' => 'validate_field', 'label' => 'Favicon', 'name' => "photos[favicon]", 'value' => '', 'image' => url($faviconPath), 'custom_span2' =>$result['favicon'], 'custom_company_favicon' => $result['favicon']],
      		['type' => 'textarea', 'class' => 'validate_field', 'label' => 'Head Code', 'name' => 'head_code', 'value' => $result['head_code']],
      		['type' => 'select', 'options' => $currency, 'class' => 'validate_field', 'label' => 'Default Currency', 'name' => 'default_currency', 'value' => $result['default_currency']],
            ['type' => 'select', 'options' => $language, 'class' => 'validate_field', 'label' => 'Default Language', 'name' => 'default_language', 'value' => $result['default_language']],
            ['type' => 'select', 'options' => ['yes' => 'Yes', 'no' => 'No'], 'class' => 'validate_field', 'label' => 'Auto Approval Properties', 'name' => 'auto_approval', 'value' => $result['auto_approval']],
			
			
      		['type' => 'textarea', 'class' => 'validate_field', 'label' => 'Invoice Description', 'name' => 'invoice_description', 'value' => $result['invoice_description']],
			['type' => 'text', 'class' => '', 'label' => 'Guest Payment Expiration Days', 'name' => 'guest_payment_expiration_time', 'value' => $result['guest_payment_expiration_time']],
            ['type' => 'select', 'options' => ['yes' => 'Yes', 'no' => 'No'], 'class' => 'validate_field', 'label' => 'Enable Captcha', 'name' => 'enable_captcha', 'value' => $result['enable_captcha']],

			
			['type' => 'file', 'class' => '', 'label' => 'User Login/Sign Up Image', 'name' => "photos[user_login_img]", 'value' => '', 'image' => url($user_login_img_Path), 'custom_span' =>$result['user_login_img'], 'custom_company_logo' => $result['user_login_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Admin Login Image', 'name' => "photos[admin_login_img]", 'value' => '', 'image' => url($admin_login_img_Path), 'custom_span' =>$result['admin_login_img'], 'custom_company_logo' => $result['admin_login_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'List Your Space', 'name' => "photos[list_your_space]", 'value' => '', 'image' => url($list_your_space), 'custom_span' =>$result['list_your_space'], 'custom_company_logo' => $result['list_your_space']] ,
			['type' => 'file', 'class' => '', 'label' => 'Rooms and Beds', 'name' => "photos[description_img]", 'value' => '', 'image' => url($description_img), 'custom_span' =>$result['description_img'], 'custom_company_logo' => $result['description_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Description', 'name' => "photos[hosting_third_img]", 'value' => '', 'image' => url($hosting_third_img), 'custom_span' =>$result['hosting_third_img'], 'custom_company_logo' => $result['hosting_third_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'location', 'name' => "photos[hosting_fourth_img]", 'value' => '', 'image' => url($hosting_fourth_img), 'custom_span' =>$result['hosting_fourth_img'], 'custom_company_logo' => $result['hosting_fourth_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Amenities', 'name' => "photos[hosting_fifth_img]", 'value' => '', 'image' => url($hosting_fifth_img), 'custom_span' =>$result['hosting_fifth_img'], 'custom_company_logo' => $result['hosting_fifth_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Photos & Videos', 'name' => "photos[hosting_sixth_img]", 'value' => '', 'image' => url($hosting_sixth_img), 'custom_span' =>$result['hosting_sixth_img'], 'custom_company_logo' => $result['hosting_sixth_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Price', 'name' => "photos[hosting_seventh_img]", 'value' => '', 'image' => url($hosting_seventh_img), 'custom_span' =>$result['hosting_seventh_img'], 'custom_company_logo' => $result['hosting_seventh_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Book your space', 'name' => "photos[hosting_eighth_img]", 'value' => '', 'image' => url($hosting_eighth_img), 'custom_span' =>$result['hosting_eighth_img'], 'custom_company_logo' => $result['hosting_eighth_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Calendar', 'name' => "photos[hosting_ninth_img]", 'value' => '', 'image' => url($hosting_ninth_img), 'custom_span' =>$result['hosting_ninth_img'], 'custom_company_logo' => $result['hosting_ninth_img']] ,

			
			['type' => 'file', 'class' => '', 'label' => 'List Your Experience', 'name' => "photos[experience_first_img]", 'value' => '', 'image' => url($experience_first_img), 'custom_span' =>$result['experience_first_img'], 'custom_company_logo' => $result['experience_first_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Experience Type and Duration', 'name' => "photos[experience_second_img]", 'value' => '', 'image' => url($experience_second_img), 'custom_span' =>$result['experience_second_img'], 'custom_company_logo' => $result['experience_second_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Description (Experience)', 'name' => "photos[experience_third_img]", 'value' => '', 'image' => url($experience_third_img), 'custom_span' =>$result['experience_third_img'], 'custom_company_logo' => $result['experience_third_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'location (Experience)', 'name' => "photos[experience_fourth_img]", 'value' => '', 'image' => url($experience_fourth_img), 'custom_span' =>$result['experience_fourth_img'], 'custom_company_logo' => $result['experience_fourth_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Inclusion/Exclusion', 'name' => "photos[experience_fifth_img]", 'value' => '', 'image' => url($experience_fifth_img), 'custom_span' =>$result['experience_fifth_img'], 'custom_company_logo' => $result['experience_fifth_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Photos & Videos (Experience)', 'name' => "photos[experience_sixth_img]", 'value' => '', 'image' => url($experience_sixth_img), 'custom_span' =>$result['experience_sixth_img'], 'custom_company_logo' => $result['experience_sixth_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Price (Experience)', 'name' => "photos[experience_seventh_img]", 'value' => '', 'image' => url($experience_seventh_img), 'custom_span' =>$result['experience_seventh_img'], 'custom_company_logo' => $result['experience_seventh_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Book your space (Experience)', 'name' => "photos[experience_eighth_img]", 'value' => '', 'image' => url($experience_eighth_img), 'custom_span' =>$result['experience_eighth_img'], 'custom_company_logo' => $result['experience_eighth_img']] ,
			['type' => 'file', 'class' => '', 'label' => 'Calendar (Experience)', 'name' => "photos[experience_ninth_img]", 'value' => '', 'image' => url($experience_ninth_img), 'custom_span' =>$result['experience_ninth_img'], 'custom_company_logo' => $result['experience_ninth_img']] ,


			
			['type' => 'file', 'class' => '', 'label' => 'Home (Try Hosting Image)', 'name' => "photos[try_hosting_img]", 'value' => '', 'image' => url($try_hosting_img), 'custom_span' =>$result['try_hosting_img'], 'custom_company_logo' => $result['try_hosting_img']] ,

            ['type' => 'select', 'options' => ['yes' => 'Yes', 'no' => 'No'], 'class' => 'validate_field', 'label' => 'Enable Facebook', 'name' => 'enable_facebook', 'value' => $result['enable_facebook']],
            ['type' => 'select', 'options' => ['yes' => 'Yes', 'no' => 'No'], 'class' => 'validate_field', 'label' => 'Enable Google', 'name' => 'enable_google', 'value' => $result['enable_google']],

            ['type' => 'select', 'options' => ['Yes' => 'Yes', 'no' => 'No'], 'class' => 'validate_field', 'label' => 'Enable Experience', 'name' => 'enable_experience', 'value' => $result['enable_experience']],
            ['type' => 'select', 'options' => ['old_home' => 'Old Home Page', 'new_home' => 'New Home Page'], 'class' => 'validate_field', 'label' => 'Select Home Page', 'name' => 'homepage_type', 'value' => $result['homepage_type']],

            ['type' => 'select', 'options' => ['Yes' => 'Yes', 'no' => 'No'], 'class' => 'validate_field', 'label' => 'Enable Cookies', 'name' => 'enable_cookies', 'value' => $result['enable_cookies']],

			['type' => 'text', 'class' => '', 'label' => 'Primary Color', 'name' => 'colorpicker', 'value' =>$result['colorpicker']],
			
			
		]
	];
@endphp
@include("admin.common.form.setting", $form_data)

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/css/bootstrap-colorpicker.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/js/bootstrap-colorpicker.min.js"></script>  
<script type="text/javascript">
  $('.colorpicker').colorpicker({});
</script>
<script type="text/javascript">
   $(document).ready(function () {

            $('#general_form').validate({
                rules: {
                    name: {
                        required: true
                    },
                    'photos[logo]': {
                        //extension: "jpg|png|jpeg"
                        accept: "image/jpg,image/jpeg,image/png,image/gif"
                        //accept: "image/*"
                    },
                    'photos[favicon]': {
                        extension: "jpg|png|jpeg|ico"
                    },
                    default_currency: {
                        required: true
                    },
                    default_language: {
                        required: true
                    }
                },
                messages: {
                    'photos[logo]': {
                        accept: 'The file must be an image (jpg, jpeg, png or gif)'
                    },
                    'photos[favicon]': {
                        extension: 'The file must be an image (jpg, jpeg, png or ico)'
                    }
                } 
            });

        });

        $('.remove_logo_preview').on("click", function(){
        var image = $('#hidden_company_logo').attr('data-rel');
        var token = $('input[name="_token"]').val();

        if(image){
                console.log("hellow");
                $.ajax({
                url : "settings/delete_logo",
                type : "post",
                async : false,
                data: { 
                    'company_logo' : image,
                    "_token": token
                },
                dataType : 'json',
                success: function(reply)
                {
                    if (reply.success == 1){
                        alert(reply.message);
                        location.reload();

                    }else{
                        alert(reply.message);
                        location.reload();

                    }
                }
                });
            }
        
        });

        $('.remove_favicon_preview').on("click", function(){
        var image = $('#hidden_company_favicon').attr('data-rel');
        var token = $('input[name="_token"]').val();
        if(image){
                $.ajax({
                url : "settings/delete_favicon",
                type : "post",
                async : false,
                data: { 'company_favicon' : image, '_token' : token},
                dataType : 'json',
                success: function(reply)
                {
                    if (reply.success == 1){
                        alert(reply.message);
                        location.reload();

                    }else{
                        alert(reply.message);
                        location.reload();

                    }
                }
                });
            }
        
        });

</script>