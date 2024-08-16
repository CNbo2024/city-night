<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\{
    Currency,
    Language,
    Settings,
    StartingCities,
    JoinUs,
    Banners,
    Page
};

use View, Config, Schema, Auth, App, Session, Validator, Cache;


class SetDataServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (env('DB_DATABASE') != '') {
            if (Schema::hasTable('currency')) {
                $this->currency();
            }
            
            if (Schema::hasTable('language')) {
                $this->language();
            }
            
            if (Schema::hasTable('settings')) {
                $this->settings();
                $this->api_info_set();
            }
            if (Schema::hasTable('pages')) {
                $this->pages();
            }
            
            if (Schema::hasTable('starting_cities')) {
                $this->destination();
            }
            
            $this->creditcard_validation();
            
        }
    }

    public function creditcard_validation()
    {
    
        Validator::extend('expires', function ($attribute, $value, $parameters, $validator) {
            $input      = $validator->getData();
            $expiryDate = gmdate('Ym', gmmktime(0, 0, 0, (int) array_get($input, $parameters[0]), 1, (int) array_get($input, $parameters[1])));
            return ($expiryDate > gmdate('Ym')) ? true : false;
        });

        Validator::extend('validate_cc', function ($attribute, $value, $parameters) {
            $str = '';
            foreach (array_reverse(str_split($value)) as $i => $c) {
                $str .= $i % 2 ? $c * 2 : $c;
            }
            return array_sum(str_split($str)) % 10 === 0;
        });
    }

    public function register()
    {
        //
    }
    
   public function currency()
    {
        ini_set('max_execution_time', 300);
        $currency = Currency::where('status', '=', 'Active')->pluck('code', 'code');
        View::share('currency', $currency);

        $currencies = Currency::where('status', '=', 'Active')->select('code', 'name', 'symbol')->get();
        View::share('currencies', $currencies);

        $ip      = $_SERVER["REMOTE_ADDR"] ?? ' ';
        $result  = @unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));

        if (isset($result['geoplugin_currencyCode']))
        {
            $default_currency = Currency::where('status', '=', 'Active')->where('code', '=', $result['geoplugin_currencyCode'])->first();
            if (!empty($default_currency)) 
            {
                $default_currency = Currency::where('status', '=', 'Active')->where('default', '=', '1')->first();
            }
        }
        else
        {
            $default_currency = Currency::where('status', '=', 'Active')->where('default', '=', '1')->first();
        }
      
      
        if (!$default_currency) {
            $default_currency = Currency::where('status', '=', 'Active')->first();
        }
        if (isset($default_currency->code)) {
            Session::put('currency', $default_currency->code);
            $symbol = Currency::code_to_symbol($default_currency->code);
            Session::put('symbol', $symbol);
        }
        View::share('default_currency', $default_currency);
        
        if (isset($result['geoplugin_countryCode']))
        {
            View::share('default_country', $result['geoplugin_countryCode']);
        }
        else
        {
            View::share('default_country', 'us');
        } 
        
      
    }
    
    public function language()
    {
        $language = Language::where('status', '=', 'Active')->pluck('name', 'short_name');
        View::share('languagefoot', $language);
        
        $default_language = Language::where('status', '=', 'Active')->where('default', '=', '1')->limit(1)->get();
        View::share('default_language', $default_language);
        if ($default_language->count() > 0) {
            Session::put('language', $default_language[0]->value);
            App::setLocale($default_language[0]->value);
        }
    }
    
    public function pages()
    {
        $footer_first  = Page::where('position', 'first')->where('status', 'Active')->get();
        $footer_second = Page::where('position', 'second')->where('status', 'Active')->get();
        View::share('footer_first', $footer_first);
        View::share('footer_second', $footer_second);
    }

    public function destination()
    {
        $popular_cities  = StartingCities::where('status', 'Active')->get();
        View::share('popular_cities', $popular_cities);
    }
    
    public function api_info_set()
    {
        $google   = Settings::where('type', 'google')->pluck('value', 'name')->toArray();
        $facebook = Settings::where('type', 'facebook')->pluck('value', 'name')->toArray();
        if (isset($google['client_id'])) {
            \Config::set(['services.google' => [
                    'client_id' => $google['client_id'],
                    'client_secret' => $google['client_secret'],
                    'redirect' => url('/googleAuthenticate'),
                    ]
                ]);
        }

        if (isset($facebook['client_id'])) {
             \Config::set(['services.facebook' => [
                        'client_id' => $facebook['client_id'],
                        'client_secret' => $facebook['client_secret'],
                        'redirect' => url('/facebookAuthenticate'),
                        ]
                        ]);
        }
    }


    public function settings()
    { 
        $settings = Settings::getAll();
        if (!empty($settings)) {
            
            // General settings
            $general = $settings->where('type', 'general')->pluck('value', 'name')->toArray();
            
            $name = $general['name'] ?? env('APP_NAME', 'Vacation Rental');

            if (!defined('SITE_NAME')) {
                define('SITE_NAME', $name);
            }
            View::share('site_name', $name);
            Config::set('site_name', $name);

 
            //App logo
            if (!empty($general['logo']) && file_exists(public_path('front/images/logos/'. $general['logo']))) {
                $logo = url('public/front/images/logos/'. $general['logo']);
            } else {
                $logo = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/front/images/logos/logo.png');
            }
            if (!defined('LOGO_URL')) {
                define('LOGO_URL', $logo);
            }
            View::share('logo', $logo);

			
			if (!empty($general['light_logo']) && file_exists(public_path('front/images/logos/'. $general['light_logo']))) {
                $light_logo = url('public/front/images/logos/'. $general['light_logo']);
            } else {
                $light_logo = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/front/images/logos/logo.png');
            }
			View::share('light_logo', $light_logo);

			if (!empty($general['colorpicker'])) {
                $primary_color = $general['colorpicker'];
            } else {
                $primary_color = "#ed3615";
            }
			if (!defined('PRIMARY_COLOR')) {
                define('PRIMARY_COLOR', $primary_color);
            }
			View::share('primary_color', $primary_color);
            Session::put('primary_color', $primary_color);
            
            //invoice desc
            if (!empty($general['invoice_description'])) {
                $invoice_description = $general['invoice_description'];
            } else {
                $invoice_description = "";
            }
			if (!defined('invoice_description')) {
                define('invoice_description', $invoice_description);
            }
			View::share('invoice_description', $invoice_description);
			
			
			if (!empty($general['guest_payment_expiration_time'])) {
                $guest_payment_expiration_time = $general['guest_payment_expiration_time'];
            } else {
                $guest_payment_expiration_time = "";
            }
			if (!defined('guest_payment_expiration_time')) {
                define('guest_payment_expiration_time', $guest_payment_expiration_time);
            }
			View::share('guest_payment_expiration_time', $guest_payment_expiration_time);


	        if (!empty($general['enable_captcha'])) {
                $enable_captcha = $general['enable_captcha'];
            } else {
                $enable_captcha = "";
            }
			if (!defined('enable_captcha')) {
                define('enable_captcha', $enable_captcha);
            }
			View::share('enable_captcha', $enable_captcha);
			
			
            if (!empty($general['enable_facebook'])) {
                $enable_facebook = $general['enable_facebook'];
            } else {
                $enable_facebook = "";
            }
			if (!defined('enable_facebook')) {
                define('enable_facebook', $enable_facebook);
            }
			View::share('enable_facebook', $enable_facebook);
			
			
			if (!empty($general['enable_google'])) {
                $enable_google = $general['enable_google'];
            } else {
                $enable_google = "";
            }
			if (!defined('enable_google')) {
                define('enable_google', $enable_google);
            }
			View::share('enable_google', $enable_google);
			
			if (!empty($general['enable_experience'])) {
                $enable_experience = $general['enable_experience'];
            } else {
                $enable_experience = "";
            }
			if (!defined('enable_experience')) {
                define('enable_experience', $enable_experience);
            }
			View::share('enable_experience', $enable_experience);
			
			if (!empty($general['enable_cookies'])) {
                $enable_cookies = $general['enable_cookies'];
            } else {
                $enable_cookies = "";
            }
			if (!defined('enable_cookies')) {
                define('enable_cookies', $enable_cookies);
            }
			View::share('enable_cookies', $enable_cookies);
			
			
			if (!empty($general['homepage_type'])) {
                $homepage_type = $general['homepage_type'];
            } else {
                $homepage_type = "";
            }
			if (!defined('homepage_type')) {
                define('homepage_type', $homepage_type);
            }
			View::share('homepage_type', $homepage_type);


            //App email logo
            if (!empty($general['email_logo']) && file_exists(public_path('front/images/logos/'. $general['email_logo']))) {
                $emailLogo = url('public/front/images/logos/'. $general['email_logo']);
            } else {
                $emailLogo = env('APP_EMAIL_LOGO_URL') != '' ? env('APP_EMAIL_LOGO_URL') : url('public/front/images/logos/email_logo.png');
            }
            if (!defined('EMAIL_LOGO_URL')) {
                define('EMAIL_LOGO_URL', $emailLogo);
            }

            //App head code/Analytics code
            $headCode = !empty($general['head_code']) ? $general['head_code'] : env('APP_HEAD_CODE', '');
            View::share('head_code', $headCode);
          
            //App favicon
            if (!empty($general['favicon']) && file_exists(public_path('front/images/logos/'. $general['favicon']))) {
              
                $favicon = url('public/front/images/logos/'. $general['favicon']);
            } else {
                $favicon = env('APP_FAVICON_URL') != '' ? env('APP_FAVICON_URL') : url('public/front/images/logos/favicon.png');
            }
            View::share('favicon', $favicon);
			
			if (!empty($general['user_login_img']) && file_exists(public_path('front/images/logos/'. $general['user_login_img']))) {
                $user_login_img = url('public/front/images/logos/'. $general['user_login_img']);
            } else {
                $user_login_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/front/images/login-bg.jpg');
            }
			View::share('user_login_img', $user_login_img);
			
			if (!empty($general['admin_login_img']) && file_exists(public_path('front/images/logos/'. $general['admin_login_img']))) {
                $admin_login_img = url('public/front/images/logos/'. $general['admin_login_img']);
            } else {
                $admin_login_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/front/images/admin-login-bg.png');
            }
			View::share('admin_login_img', $admin_login_img);
			
			if (!empty($general['list_your_space']) && file_exists(public_path('front/images/logos/'. $general['list_your_space']))) {
                $first_step = url('public/front/images/logos/'. $general['list_your_space']);
            } else {
                $first_step = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step1.jpg');
            }
			View::share('first_step', $first_step);
			
			if (!empty($general['description_img']) && file_exists(public_path('front/images/logos/'. $general['description_img']))) {
                $second_step = url('public/front/images/logos/'. $general['description_img']);
            } else {
                $second_step = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step2.jpg');
            }
			View::share('second_step', $second_step);
			
			if (!empty($general['hosting_third_img']) && file_exists(public_path('front/images/logos/'. $general['hosting_third_img']))) {
                $third_step = url('public/front/images/logos/'. $general['hosting_third_img']);
            } else {
                $third_step = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step3.jpg');
            }
			View::share('third_step', $third_step);
			
			if (!empty($general['hosting_fourth_img']) && file_exists(public_path('front/images/logos/'. $general['hosting_fourth_img']))) {
                $fourth_step = url('public/front/images/logos/'. $general['hosting_fourth_img']);
            } else {
                $fourth_step = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step4.jpg');
            }
			View::share('fourth_step', $fourth_step);
			
			if (!empty($general['hosting_fifth_img']) && file_exists(public_path('front/images/logos/'. $general['hosting_fifth_img']))) {
                $fifth_step = url('public/front/images/logos/'. $general['hosting_fifth_img']);
            } else {
                $fifth_step = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step5.jpg');
            }
			View::share('fifth_step', $fifth_step);
			
			if (!empty($general['hosting_sixth_img']) && file_exists(public_path('front/images/logos/'. $general['hosting_sixth_img']))) {
                $sixth_step = url('public/front/images/logos/'. $general['hosting_sixth_img']);
            } else {
                $sixth_step = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step6.jpg');
            }
			View::share('sixth_step', $sixth_step);
			
			if (!empty($general['hosting_seventh_img']) && file_exists(public_path('front/images/logos/'. $general['hosting_seventh_img']))) {
                $seventh_step = url('public/front/images/logos/'. $general['hosting_seventh_img']);
            } else {
                $seventh_step = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step7.jpg');
            }
			View::share('seventh_step', $seventh_step);
			
			if (!empty($general['hosting_eighth_img']) && file_exists(public_path('front/images/logos/'. $general['hosting_eighth_img']))) {
                $eighth_step = url('public/front/images/logos/'. $general['hosting_eighth_img']);
            } else {
                $eighth_step = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step8.jpg');
            }
			View::share('eighth_step', $eighth_step);
			
			if (!empty($general['hosting_ninth_img']) && file_exists(public_path('front/images/logos/'. $general['hosting_ninth_img']))) {
                $ninth_step = url('public/front/images/logos/'. $general['hosting_ninth_img']);
            } else {
                $ninth_step = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step9.jpg');
            }
			View::share('ninth_step', $ninth_step);
			
			if (!empty($general['try_hosting_img']) && file_exists(public_path('front/images/logos/'. $general['try_hosting_img']))) {
                $try_hosting_img = url('public/front/images/logos/'. $general['try_hosting_img']);
            } else {
                $try_hosting_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/try-hosting.jpg');
            }
			View::share('try_hosting_img', $try_hosting_img);
			
			
				
			if (!empty($general['experience_first_img']) && file_exists(public_path('front/images/logos/'. $general['experience_first_img']))) {
                $experience_first_img = url('public/front/images/logos/'. $general['experience_first_img']);
            } else {
                $experience_first_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step1.jpg');
            }
			View::share('experience_first_img', $experience_first_img);
			
			if (!empty($general['experience_second_img']) && file_exists(public_path('front/images/logos/'. $general['experience_second_img']))) {
                $experience_second_img = url('public/front/images/logos/'. $general['experience_second_img']);
            } else {
                $experience_second_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step2.jpg');
            }
			View::share('experience_second_img', $experience_second_img);
			
			if (!empty($general['experience_third_img']) && file_exists(public_path('front/images/logos/'. $general['experience_third_img']))) {
                $experience_third_img = url('public/front/images/logos/'. $general['experience_third_img']);
            } else {
                $experience_third_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step3.jpg');
            }
			View::share('experience_third_img', $experience_third_img);
			
			if (!empty($general['experience_fourth_img']) && file_exists(public_path('front/images/logos/'. $general['experience_fourth_img']))) {
                $experience_fourth_img = url('public/front/images/logos/'. $general['experience_fourth_img']);
            } else {
                $experience_fourth_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step4.jpg');
            }
			View::share('experience_fourth_img', $experience_fourth_img);
			
			if (!empty($general['experience_fifth_img']) && file_exists(public_path('front/images/logos/'. $general['experience_fifth_img']))) {
                $experience_fifth_img = url('public/front/images/logos/'. $general['experience_fifth_img']);
            } else {
                $experience_fifth_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step5.jpg');
            }
			View::share('experience_fifth_img', $experience_fifth_img);
			
			if (!empty($general['experience_sixth_img']) && file_exists(public_path('front/images/logos/'. $general['experience_sixth_img']))) {
                $experience_sixth_img = url('public/front/images/logos/'. $general['experience_sixth_img']);
            } else {
                $experience_sixth_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step6.jpg');
            }
			View::share('experience_sixth_img', $experience_sixth_img);
			
			if (!empty($general['experience_seventh_img']) && file_exists(public_path('front/images/logos/'. $general['experience_seventh_img']))) {
                $experience_seventh_img = url('public/front/images/logos/'. $general['experience_seventh_img']);
            } else {
                $experience_seventh_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step7.jpg');
            }
			View::share('experience_seventh_img', $experience_seventh_img);
			
			if (!empty($general['experience_eighth_img']) && file_exists(public_path('front/images/logos/'. $general['experience_eighth_img']))) {
                $experience_eighth_img = url('public/front/images/logos/'. $general['experience_eighth_img']);
            } else {
                $experience_eighth_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step8.jpg');
            }
			View::share('experience_eighth_img', $experience_eighth_img);
			
			if (!empty($general['experience_ninth_img']) && file_exists(public_path('front/images/logos/'. $general['experience_ninth_img']))) {
                $experience_ninth_img = url('public/front/images/logos/'. $general['experience_ninth_img']);
            } else {
                $experience_ninth_img = env('APP_LOGO_URL') != '' ? env('APP_LOGO_URL') : url('public/images/step9.jpg');
            }
			View::share('experience_ninth_img', $experience_ninth_img);


            // Google Map Key
            $map     = $settings->where('type', 'googleMap')->pluck('value', 'name')->toArray();
            if (!empty($map['key'])) {
                    View::share('map_key', $map['key']);
                    define('MAP_KEY', $map['key']);
            }

            // Join us
            $join_us = Settings::where('type', 'join_us')->get();
            View::share('join_us', $join_us);
            
            $preferences       = $settings->where('type', 'preferences')->pluck('value', 'name')->toArray();
            $front_date_format_type = $preferences['front_date_format_type'];
             View::share('front_date_format_type', $front_date_format_type);

            View::share('settings', $settings);
        }

        //App Banner 
$banner = Banners::where('status', 'Active')->first();

if ($banner) {
    $banner_image = url('public/front/images/banners/'.$banner->image);
    $heading = $banner->heading;
    $subheading = $banner->subheading;
} else {
    // Define valores padrão caso não haja nenhum banner ativo
    $banner_image = url('public/images/default-banner.jpg');
    $heading = 'Default Heading';
    $subheading = 'Default Subheading';
}

if ( !defined('BANNER_URL') ) {
    define('BANNER_URL', $banner_image);
    define('HEADING', $heading);
    define('SUBHEADING', $subheading);


        }

    }
}
