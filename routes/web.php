<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great! 
|
*/

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

Route::post('receipt', 'LinkserController@receipt');

Route::get('/promotion', function () {
	return DB::table('promotions')->where('code', request()->code)->first();
});

Route::get('set-color', function () {
	$properties = App\Models\Properties::get();

	foreach ($properties as $property) {
		$property->color = rand_color();
		$property->save();
	}
});

Route::get('size', function () {
	return "        <meta name='viewport' content='user-scalable=no, width=device-width'><script>document.write(window.innerWidth)</script>";
});


Route::match(array('GET', 'POST'),'create-users-wallet', 'HomeController@walletUser');

// QR
Route::get('qr', 'QRController@index');
Route::post('qr/download', 'QRController@download');
Route::get('qr/status', 'QRController@status');

//cron job
Route::get('cron/payout', 'CronController@payout');
Route::get('cron', 'CronController@index');
Route::get('import', 'CronController@importDump');
Route::get('cron/ical-synchronization','CronController@iCalendarSynchronization');

Route::get('/deletePhoto/{photo_id}/{property_id}', function ($photo_id, $property_id) {
	DB::table('property_photos')->where('id', $photo_id)->delete();
	return redirect('/listing/' . $property_id . '/photos');
});

//user can view it anytime with or without logged in
Route::group(['middleware' => ['locale']], function () { 
   
    $general = DB::table('settings')->where('type', 'general')->pluck('value', 'name')->toArray();
    if($general['homepage_type'] == "old_home")
    {
        Route::get('/', 'HomeController@index');
    }
    else
    {
        Route::get('/', 'SearchController@index');
    }
    
    Route::get('new-home', 'SearchController@index');
    Route::get('old-home', 'HomeController@index');

  	Route::match(['get', 'post'], 'search/result', 'SearchController@searchResult');
	Route::get('search', 'SearchController@index');
	
	Route::get('expsearch', 'SearchController@expindex');
	Route::post('expsearch/result', 'SearchController@expsearchResult');
	
	Route::match(array('GET', 'POST'),'properties/{id}/{slug}', 'PropertyController@single')->name('property.single');
	Route::match(array('GET', 'POST'),'experiences/{id}/{slug}', 'PropertyController@single')->name('property.single');

	Route::match(array('GET', 'POST'),'property/get-price', 'PropertyController@getPrice');
	Route::get('set-slug/', 'PropertyController@set_slug');
	Route::get('signup', 'LoginController@signup');
	Route::post('/checkUser/check', 'LoginController@check')->name('checkUser.check');
    Route::match(array('GET', 'POST'),'updateqty', 'ExperienceController@updateqty');
    Route::match(array('GET', 'POST'),'remove-from-cart', 'ExperienceController@remove_cart');

    Route::post('cohosts', 'PropertyController@cohosts');
	Route::get('hosts', 'HostController@index');
	Route::get('calendar', 'HostController@calendar');

	Route::get('property-price-date', 'PropertyPriceDateController@get');
	Route::get('property-price-date/delete', 'PropertyPriceDateController@delete');
	Route::post('property-price-date', 'PropertyPriceDateController@save');
	Route::get('smart-price', 'SmartPriceController@get');

	Route::post('/blocked-dates', 'BlockedDatesController@save');
	Route::get('/unlock', 'BlockedDatesController@update');

	Route::post('saveCi', function () {
		$user_id = auth()->user()->id;
		$user = User::find($user_id);
		$user->ci = request()->ci;
		$user->save();
	});
});

//Auth::routes();

Route::post('set_session', 'HomeController@setSession');

//only can view if admin is logged in
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['guest:admin']], function(){
	Route::get('/', function(){
        return Redirect::to('admin/dashboard');
	});

	Route::match(['GET', 'POST'], 'min-request', 'MinRequestController@index');
	
	Route::group(['middleware' => 'permission:manage_penalty'], function () {
		Route::get('host_penalty', 'PenaltiesController@host_penalty');
		Route::get('guest_penalty', 'PenaltiesController@guest_penalty');
	});
	
	Route::get('document_verification', 'AdminController@doc_index');
	Route::get('approve_document_verification', 'AdminController@approve_document_verification');
	Route::get('properties/{id}/{status}', 'PropertiesController@change_status');

	Route::get('customer/approve/{id}', 'AdminController@approve_doc');
	Route::get('customer/disapprove/{id}', 'AdminController@disapprove_doc');
	

	Route::resource('tips', 'TipController');
	Route::resource('identify-verification', 'IdentifyVerificationController');
	Route::get('identify-verification/{status}/{id}/{message?}', 'IdentifyVerificationController@status');
	Route::resource('cards', 'CardController');
	Route::resource('promotion', 'PromotionController');

	Route::get('promotion/list/{id}', 'PromotionController@list');
	
    Route::match(array('GET', 'POST'), 'profile', 'AdminController@profile');
    Route::get('logout', 'AdminController@logout');
	Route::get('dashboard', 'DashboardController@index');
	Route::get('customers', 'CustomerController@index')->middleware(['permission:customers']);
	Route::get('customers/customer_search', 'CustomerController@searchCustomer')->middleware(['permission:customers']);
    Route::post('add-ajax-customer', 'CustomerController@ajaxCustomerAdd')->middleware(['permission:add_customer']);
	Route::match(array('GET', 'POST'), 'add-customer', 'CustomerController@add')->middleware(['permission:add_customer']);

	Route::group(['middleware' => 'permission:edit_customer'], function () {
		Route::match(array('GET', 'POST'), 'edit-customer/{id}', 'CustomerController@update');
		//Route::match(array('GET', 'POST'), 'delete-customer/{id}', 'CustomerController@user_delete');
		
		Route::match(array('GET', 'POST'), 'delete-customer-view/{id}', 'CustomerController@user_delete_view');
		Route::match(array('GET', 'POST'), 'delete-customer', 'CustomerController@user_delete');

		Route::get('customer/properties/{id}', 'CustomerController@customerProperties');
		Route::get('customer/trips/{id}', 'CustomerController@customerBookings');
	    Route::get('customer/mybooking/{id}', 'CustomerController@customermyBookings');

		Route::post('customer/bookings/property_search', 'BookingsController@searchProperty');
		Route::get('customer/payouts/{id}', 'CustomerController@customerPayouts');
		Route::get('customer/payment-methods/{id}', 'CustomerController@paymentMethods');
		Route::get('customer/wallet/{id}', 'CustomerController@customerWallet');

		Route::get('customer/properties/{id}/property_list_csv', 'PropertiesController@propertyCsv');
		Route::get('customer/properties/{id}/property_list_pdf', 'PropertiesController@propertyPdf');

		Route::get('customer/bookings/{id}/booking_list_csv', 'BookingsController@bookingCsv');
		Route::get('customer/bookings/{id}/booking_list_pdf', 'BookingsController@bookingPdf');

		Route::get('customer/payouts/{id}/payouts_list_pdf', 'PayoutsController@payoutsPdf');
		Route::get('customer/payouts/{id}/payouts_list_csv', 'PayoutsController@payoutsCsv');

		Route::get('customer/customer_list_csv', 'CustomerController@customerCsv');
		Route::get('customer/customer_list_pdf', 'CustomerController@customerPdf');
	});
	
	Route::group(['middleware' => 'permission:manage_messages'], function () {
		Route::get('messages', 'AdminController@customerMessage');
		Route::match(array('GET', 'POST'), 'delete-message/{id}', 'AdminController@deleteMessage');
		Route::match(array('GET','POST'), 'send-message-email/{id}', 'AdminController@sendEmail');
		Route::match(['get', 'post'],'upload_image','AdminController@uploadImage')->name('upload');
		Route::get('messaging/host/{id}', 'AdminController@hostMessage');
        Route::post('reply/{id}', 'AdminController@reply');
    });

	Route::get('properties', 'PropertiesController@index')->middleware(['permission:properties']);
	Route::match(array('GET', 'POST'), 'add-properties', 'PropertiesController@add')->middleware(['permission:add_properties']);
	Route::get('properties/property_list_csv', 'PropertiesController@propertyCsv');
	Route::get('properties/property_list_pdf', 'PropertiesController@propertyPdf');

	/* Experience */
	Route::get('experience', 'ExperienceController@index')->middleware(['permission:experience']);
	Route::get('experience/property_list_csv', 'ExperienceController@propertyCsv');
	Route::get('experience/property_list_pdf', 'ExperienceController@propertyPdf');
	
	Route::group(['middleware' => 'permission:manage_experience_category'], function () {
		Route::get('experience/experience_category', 'ExperienceController@cat_index');
		Route::match(array('GET', 'POST'), 'experience/add_experience_category', 'ExperienceController@cat_add');
		Route::match(array('GET', 'POST'), 'experience/edit_experience_category/{id}', 'ExperienceController@cat_update');
		Route::get('experience/delete_experience_category/{id}', 'ExperienceController@cat_delete');
	});
	
	Route::group(['middleware' => 'permission:manage_inclusion'], function () {
		Route::get('experience/inclusion', 'ExperienceController@inclusion_index');
		Route::match(array('GET', 'POST'), 'experience/add_inclusion', 'ExperienceController@inclusion_add');
		Route::match(array('GET', 'POST'), 'experience/edit_inclusion/{id}', 'ExperienceController@inclusion_update');
		Route::get('experience/delete_inclusion/{id}', 'ExperienceController@inclusion_delete');
	});
	
	Route::group(['middleware' => 'permission:manage_exclusion'], function () {
		Route::get('experience/exclusion', 'ExperienceController@exclusion_index');
		Route::match(array('GET', 'POST'), 'experience/add_exclusion', 'ExperienceController@exclusion_add');
		Route::match(array('GET', 'POST'), 'experience/edit_exclusion/{id}', 'ExperienceController@exclusion_update');
		Route::get('experience/delete_exclusion/{id}', 'ExperienceController@exclusion_delete');
	});
	
	Route::group(['middleware' => 'permission:edit_experience'], function () {
		Route::match(array('GET', 'POST'),'experience/{id}/photo_message', 'ExperienceController@photoMessage');
		Route::match(array('GET', 'POST'),'experience/{id}/photo_delete', 'ExperienceController@photoDelete');
		Route::match(array('GET', 'POST'),'experience/{id}/update_status', 'ExperienceController@update_status');
		Route::match(array('POST'),'experience/photo/make_default_photo', 'ExperienceController@makeDefaultPhoto');
		Route::match(array('POST'),'experience/photo/make_photo_serial', 'ExperienceController@makePhotoSerial');
		Route::match(array('GET', 'POST'),'experience/{id}/{step}', 'ExperienceController@listing')->where(['id' => '[0-9]+','page' => 'basics|description|location|amenities|photos|pricing|calendar|details|booking']);
	});
	Route::get('delete-experience/{id}', 'ExperienceController@delete')->middleware(['permission:delete_property']);

	/* Experience */
	

	Route::group(['middleware' => 'permission:edit_properties'], function () {
		Route::match(array('GET', 'POST'),'listing/{id}/photo_message', 'PropertiesController@photoMessage');
		Route::match(array('GET', 'POST'),'listing/{id}/photo_delete', 'PropertiesController@photoDelete');
		Route::match(array('GET', 'POST'),'listing/{id}/update_status', 'PropertiesController@update_status');
		Route::match(array('POST'),'listing/photo/make_default_photo', 'PropertiesController@makeDefaultPhoto');
		Route::match(array('POST'),'listing/photo/make_photo_serial', 'PropertiesController@makePhotoSerial');
		Route::match(array('GET', 'POST'),'listing/{id}/{step}', 'PropertiesController@listing')->where(['id' => '[0-9]+','page' => 'basics|description|location|amenities|photos|pricing|calendar|details|booking']);
	});

    Route::post('ajax-calender/{id}', 'CalendarController@calenderJson');
    Route::post('ajax-calender-price/{id}', 'CalendarController@calenderPriceSet');
    //iCalender routes for admin
    Route::post('ajax-icalender-import/{id}', 'CalendarController@icalendarImport');
    Route::get('icalendar/synchronization/{id}', 'CalendarController@icalendarSynchronization');
    //iCalender routes end
	Route::match(array('GET', 'POST'), 'edit_property/{id}', 'PropertiesController@update')->middleware(['permission:edit_properties']);
	Route::get('delete-property/{id}', 'PropertiesController@delete')->middleware(['permission:delete_property']);
	Route::get('bookings', 'BookingsController@index')->middleware(['permission:manage_bookings']);
	Route::get('bookings/property_search', 'BookingsController@searchProperty')->middleware(['permission:manage_bookings']);
	Route::get('bookings/customer_search', 'BookingsController@searchCustomer')->middleware(['permission:manage_bookings']);
	//booking details
	Route::get('bookings/detail/{id}', 'BookingsController@details')->middleware(['permission:manage_bookings']);
	Route::post('bookings/pay', 'BookingsController@pay')->middleware(['permission:manage_bookings']);
	
	Route::match(array('GET', 'POST'), 'bookings/penalty_accept/{bookid}/{hostid}', 'BookingsController@penalty_accept');
	Route::match(array('GET', 'POST'), 'bookings/penalty_decline/{bookid}/{hostid}', 'BookingsController@penalty_decline');


	Route::get('booking/need_pay_account/{id}/{type}', 'BookingsController@needPayAccount');
	Route::get('booking/booking_list_csv', 'BookingsController@bookingCsv');
	Route::get('booking/booking_list_pdf', 'BookingsController@bookingPdf');
	Route::get('payouts', 'PayoutsController@index')->middleware(['permission:view_payouts']);
	Route::match(array('GET', 'POST'), 'payouts/edit/{id}', 'PayoutsController@edit');
	Route::get('payouts/details/{id}', 'PayoutsController@details');
	Route::get('payouts/payouts_list_pdf', 'PayoutsController@payoutsPdf');
	Route::get('payouts/payouts_list_csv', 'PayoutsController@payoutsCsv');
	Route::group(['middleware' => 'permission:manage_reviews'], function () {
		Route::get('reviews', 'ReviewsController@index');
		Route::match(array('GET', 'POST'), 'edit_review/{id}', 'ReviewsController@edit');
		Route::get('reviews/review_search', 'ReviewsController@searchReview');
		Route::get('reviews/review_list_csv', 'ReviewsController@reviewCsv');
		Route::get('reviews/review_list_pdf', 'ReviewsController@reviewPdf');

	});

	// Route::get('reports', 'ReportsController@index')->middleware(['permission:manage_reports']);

	// For Reporting
	Route::group(['middleware' => 'permission:view_reports'], function () {
		Route::get('sales-report', 'ReportsController@salesReports');
		Route::get('sales-analysis', 'ReportsController@salesAnalysis');
		Route::get('reports/property-search', 'ReportsController@searchProperty');
		Route::get('overview-stats', 'ReportsController@overviewStats');
		
		Route::get('sales-analysis-experience', 'ReportsController@salesAnalysisExperience');
		Route::get('reports/experience-search', 'ReportsController@searchExperience');
		Route::get('overview-stats-experience', 'ReportsController@overviewStatsExperience');
	});

	Route::group(['middleware' => 'permission:manage_amenities'], function () {
		Route::get('amenities', 'AmenitiesController@index');
		Route::match(array('GET', 'POST'), 'add-amenities', 'AmenitiesController@add');
		Route::match(array('GET', 'POST'), 'edit-amenities/{id}', 'AmenitiesController@update');
		Route::get('delete-amenities/{id}', 'AmenitiesController@delete');
	});

	Route::group(['middleware' => 'permission:manage_pages'], function () {
		Route::get('pages', 'PagesController@index');
		Route::match(array('GET', 'POST'), 'add-page', 'PagesController@add');
		Route::match(array('GET', 'POST'), 'edit-page/{id}', 'PagesController@update');
		Route::get('delete-page/{id}', 'PagesController@delete');

	});

	
	Route::group(['middleware' => 'permission:manage_admin'], function () {
		Route::get('admin-users', 'AdminController@index');
		Route::match(array('GET', 'POST'), 'add-admin', 'AdminController@add');
		Route::match(array('GET', 'POST'), 'edit-admin/{id}', 'AdminController@update');
		Route::match(array('GET', 'POST'), 'delete-admin/{id}', 'AdminController@delete');
	});

	Route::group(['middleware' => 'permission:general_setting'], function () {
		Route::match(array('GET', 'POST'), 'settings', 'SettingsController@general')->middleware(['permission:general_setting']);
		Route::match(array('GET', 'POST'), 'settings/preferences', 'SettingsController@preferences')->middleware(['permission:preference']);
		Route::post('settings/delete_logo', 'SettingsController@deleteLogo');
		Route::post('settings/delete_favicon', 'SettingsController@deleteFavIcon');
		Route::match(array('GET', 'POST'), 'settings/fees', 'SettingsController@fees')->middleware(['permission:manage_fees']);
		Route::group(['middleware' => 'permission:manage_banners'], function () {
			Route::get('settings/banners', 'BannersController@index');
			Route::match(array('GET', 'POST'), 'settings/add-banners', 'BannersController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-banners/{id}', 'BannersController@update');
			Route::get('settings/delete-banners/{id}', 'BannersController@delete');
		});

		Route::group(['middleware' => 'permission:starting_cities_settings'], function () {
			Route::get('settings/starting-cities', 'StartingCitiesController@index');
			Route::match(array('GET', 'POST'), 'settings/add-starting-cities', 'StartingCitiesController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-starting-cities/{id}', 'StartingCitiesController@update');
			Route::get('settings/delete-starting-cities/{id}', 'StartingCitiesController@delete');
		});

		Route::group(['middleware' => 'permission:manage_property_type'], function () {
			Route::get('settings/property-type', 'PropertyTypeController@index');
			Route::match(array('GET', 'POST'), 'settings/add-property-type', 'PropertyTypeController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-property-type/{id}', 'PropertyTypeController@update');
			Route::get('settings/delete-property-type/{id}', 'PropertyTypeController@delete');
		});

		Route::group(['middleware' => 'permission:space_type_setting'], function () {
			Route::get('settings/space-type', 'SpaceTypeController@index');
			Route::match(array('GET', 'POST'), 'settings/add-space-type', 'SpaceTypeController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-space-type/{id}', 'SpaceTypeController@update');
			Route::get('settings/delete-space-type/{id}', 'SpaceTypeController@delete');
		});

		Route::group(['middleware' => 'permission:manage_bed_type'], function () {
			Route::get('settings/bed-type', 'BedTypeController@index');
			Route::match(array('GET', 'POST'), 'settings/add-bed-type', 'BedTypeController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-bed-type/{id}', 'BedTypeController@update');
			Route::get('settings/delete-bed-type/{id}', 'BedTypeController@delete');
		});

		Route::group(['middleware' => 'permission:manage_currency'], function () {
			Route::get('settings/currency', 'CurrencyController@index');
			Route::match(array('GET', 'POST'), 'settings/add-currency', 'CurrencyController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-currency/{id}', 'CurrencyController@update');
			Route::get('settings/delete-currency/{id}', 'CurrencyController@delete');
		});

		Route::group(['middleware' => 'permission:manage_country'], function () {
			Route::get('settings/country', 'CountryController@index');
			Route::match(array('GET', 'POST'), 'settings/add-country', 'CountryController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-country/{id}', 'CountryController@update');
			Route::get('settings/delete-country/{id}', 'CountryController@delete');
		});

		Route::group(['middleware' => 'permission:manage_amenities_type'], function () {
			Route::get('settings/amenities-type', 'AmenitiesTypeController@index');
			Route::match(array('GET', 'POST'), 'settings/add-amenities-type', 'AmenitiesTypeController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-amenities-type/{id}', 'AmenitiesTypeController@update');
			Route::get('settings/delete-amenities-type/{id}', 'AmenitiesTypeController@delete');
		});

		Route::match(array('GET', 'POST'), 'settings/email', 'SettingsController@email')->middleware(['permission:email_settings']);



		Route::group(['middleware' => 'permission:manage_language'], function () {
			Route::get('settings/language', 'LanguageController@index');
			Route::match(array('GET', 'POST'), 'settings/add-language', 'LanguageController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-language/{id}', 'LanguageController@update');
			Route::get('settings/delete-language/{id}', 'LanguageController@delete');
		});

		Route::match(array('GET', 'POST'), 'settings/fees', 'SettingsController@fees')->middleware(['permission:manage_fees']);

		Route::group(['middleware' => 'permission:manage_metas'], function () {
			Route::get('settings/metas', 'MetasController@index');
			Route::match(array('GET', 'POST'), 'settings/edit_meta/{id}', 'MetasController@update');
		});

		Route::match(array('GET', 'POST'), 'settings/api-informations', 'SettingsController@apiInformations')->middleware(['permission:api_informations']);
		Route::match(array('GET', 'POST'), 'settings/payment-methods', 'SettingsController@paymentMethods')->middleware(['permission:payment_settings']);
		Route::match(array('GET', 'POST'), 'settings/social-links', 'SettingsController@socialLinks')->middleware(['permission:social_links']);

		Route::group(['middleware' => 'permission:manage_roles'], function () {
			Route::get('settings/roles', 'RolesController@index');
			Route::match(array('GET', 'POST'), 'settings/add-role', 'RolesController@add');
			Route::match(array('GET', 'POST'), 'settings/edit-role/{id}', 'RolesController@update');
			Route::get('settings/delete-role/{id}', 'RolesController@delete');
		});

		Route::group(['middleware' => 'permission:database_backup'], function () {
			Route::get('settings/backup', 'BackupController@index');
			Route::get('backup/save', 'BackupController@add');
			Route::get('backup/download/{id}', 'BackupController@download');
		});
		
		Route::group(['middleware' => 'permission:delete_demo_content'], function () {
    		Route::get('settings/delete-demo', 'SettingsController@delete_demo_content');
    		Route::get('settings/deletedemo', 'SettingsController@deletedemo');
        });

		Route::group(['middleware' => 'permission:manage_email_template'], function () {
			Route::get('email-template/{id}', 'EmailTemplateController@index');
			Route::post('email-template/{id}','EmailTemplateController@update');
		});

		Route::group(['middleware' => 'permission:manage_testimonial'], function () {
			Route::get('testimonials', 'TestimonialController@index');
			Route::match(array('GET', 'POST'), 'add-testimonials', 'TestimonialController@add');
			Route::match(array('GET', 'POST'), 'edit-testimonials/{id}', 'TestimonialController@update');
			Route::get('delete-testimonials/{id}', 'TestimonialController@delete');
		});
	});
});

//only can view if admin is not logged in if they are logged in then they will be redirect to dashboard
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'no_auth:admin'], function () {
    Route::get('login', 'AdminController@login');
});

//only can view if user is not logged in if they are logged in then they will be redirect to dashboard
Route::group(['middleware' => ['no_auth:users', 'locale']], function () {
    Route::get('login', 'LoginController@index');
    Route::get('auth/login', function()
    {
		return Redirect::to('login');
    });

    Route::get('googleLogin', 'LoginController@googleLogin');
    Route::get('facebookLogin', 'LoginController@facebookLogin');
    Route::get('register', 'HomeController@register');
    Route::match(array('GET', 'POST'), 'forgot_password', 'LoginController@forgotPassword');
    Route::post('create', 'UserController@create');
    Route::post('authenticate', 'LoginController@authenticate');
    Route::get('users/reset_password/{secret?}', 'LoginController@resetPassword');
    Route::post('users/reset_password', 'LoginController@resetPassword');
	Route::match(array('GET', 'POST'),'sendotp', 'LoginController@sendotp');

});

Route::get('googleAuthenticate', 'LoginController@googleAuthenticate');
Route::get('facebookAuthenticate', 'LoginController@facebookAuthenticate');

//only can view if user is logged in
Route::group(['middleware' => ['guest:users', 'locale']], function () {
	Route::match(['GET', 'POST'], 'web-scraping', 'WebScrapingController@index');

	Route::get('tips', 'TipController@index');

	Route::resource('cards', 'CardController');

    Route::get('dashboard', 'UserController@dashboard');
    Route::match(array('GET', 'POST'),'users/profile', 'UserController@profile');
    Route::match(array('GET', 'POST'),'users/profile/media', 'UserController@media');
	Route::match(array('GET', 'POST'), 'users/delete-customer/{id}', 'UserController@user_delete');

    // User verification
    Route::get('users/edit-verification', 'UserController@verification');
    Route::get('users/confirm_email/{code?}', 'UserController@confirmEmail');
    Route::get('users/new_email_confirm', 'UserController@newConfirmEmail');

    Route::get('facebookLoginVerification', 'UserController@facebookLoginVerification');
    Route::get('facebookConnect/{id}', 'UserController@facebookConnect');
    Route::get('facebookDisconnect', 'UserController@facebookDisconnectVerification');

    Route::get('googleLoginVerification', 'UserController@googleLoginVerification');
    Route::get('googleConnect/{id}', 'UserController@googleConnect');
    Route::get('googleDisconnect', 'UserController@googleDisconnect');
    // Route::get('googleAuthenticate', 'LoginController@googleAuthenticate');
    
    Route::get('documentVerification', 'UserController@documentVerification');
	Route::post('create/dropzone-image-upload','UserController@doc_upload');
	Route::post('create/dropzone-image-delete','UserController@doc_destroy');
    Route::match(array('GET', 'POST'),'docremove', 'UserController@docremove');


    Route::get('users/show/{id}', 'UserController@show');
	Route::match(array('GET', 'POST'),'users/reviews', 'UserController@reviews');
	Route::match(array('GET', 'POST'),'users/reviews_by_you', 'UserController@reviewsByYou');
    Route::match(['get', 'post'], 'reviews/edit/{id}', 'UserController@editReviews');
    Route::match(['get', 'post'], 'reviews/details', 'UserController@reviewDetails');

    Route::get('mywishlist', 'WishlistController@index');
	Route::match(array('GET', 'POST'),'wishlist', 'PropertyController@wishlist');
    Route::match(array('GET', 'POST'),'wishlistremove', 'PropertyController@wishlistremove');
    
	Route::match(array('GET', 'POST'),'deletepackage', 'ExperienceController@deletepackage');
	Route::match(array('GET', 'POST'),'deletepackagetime', 'ExperienceController@deletepackagetime');

	
    Route::match(array('GET', 'POST'),'properties', 'PropertyController@userProperties');
    Route::match(array('GET', 'POST'),'property/create', 'PropertyController@create');
	
	/* Experience */
    Route::match(array('GET', 'POST'),'experience/{id}/{step}', 'ExperienceController@listing')->where(['id' => '[0-9]+','page' => 'basics|description|location|amenities|photos|pricing|calendar|details|booking']);
    Route::match(array('GET', 'POST'),'experience', 'ExperienceController@userexperience');
    Route::match(array('GET', 'POST'),'experience/update_status', 'ExperienceController@updateStatus');
	Route::match(array('GET', 'POST'),'experience/create', 'ExperienceController@create');
	

	/* Experience */
	
    Route::match(array('GET', 'POST'),'listing/{id}/photo_message', 'PropertyController@photoMessage')->middleware(['checkUserRoutesPermissions']);
    Route::match(array('GET', 'POST'),'listing/{id}/photo_delete', 'PropertyController@photoDelete')->middleware(['checkUserRoutesPermissions']);

	Route::match(array('POST'),'listing/photo/make_default_photo', 'PropertyController@makeDefaultPhoto');

	Route::get('duplicate/{id}', 'PropertyController@duplicate');

	Route::match(array('POST'),'listing/photo/make_photo_serial', 'PropertyController@makePhotoSerial');

    Route::match(array('GET', 'POST'),'listing/update_status', 'PropertyController@updateStatus');
    Route::match(array('GET', 'POST'),'listing/{id}/{step}', 'PropertyController@listing')->where(['id' => '[0-9]+','page' => 'basics|description|location|amenities|photos|pricing|calendar|details|booking']);
    
    Route::post('ajax-calender/{id}', 'CalendarController@calenderJson');
    Route::post('ajax-calender-price/{id}', 'CalendarController@calenderPriceSet');
    //iCalendar routes start 
    Route::post('ajax-icalender-import/{id}', 'CalendarController@icalendarImport');
    Route::get('icalendar/synchronization/{id}', 'CalendarController@icalendarSynchronization');
    //iCalendar routes end 
    Route::post('currency-symbol', 'PropertyController@currencySymbol');
    Route::match(['get', 'post'], 'payments/book/{id?}', 'PaymentController@index');
    Route::post('payments/create_booking', 'PaymentController@createBooking');
	
	//Route::get('payment', [PaymentController::class,'payment'])->name('payment');
    Route::match(['get', 'post'], 'payment', 'PaymentController@payment')->name('payment');

	
    Route::get('payments/success', 'PaymentController@success');
    Route::get('payments/cancel', 'PaymentController@cancel');
    Route::get('payments/stripe', 'PaymentController@stripePayment');
    Route::post('payments/stripe-request', 'PaymentController@stripeRequest');
    Route::get('booking/{id}', 'BookingController@index')->where('id', '[0-9]+');
    Route::get('booking_payment/{id}', 'BookingController@requestPayment')->where('id', '[0-9]+');
    Route::get('booking/requested', 'BookingController@requested');
    Route::get('booking/itinerary_friends', 'BookingController@requested');
    Route::post('booking/accept/{id}', 'BookingController@accept');
    Route::post('booking/decline/{id}', 'BookingController@decline');
    Route::get('booking/expire/{id}', 'BookingController@expire');
    Route::match(['get', 'post'], 'my-bookings', 'BookingController@myBookings');
    Route::post('booking/host_cancel', 'BookingController@hostCancel');
    Route::match(['get', 'post'], 'trips/active', 'TripsController@myTrips');
    Route::get('booking/receipt', 'TripsController@receipt');
    Route::post('trips/guest_cancel', 'TripsController@guestCancel');

    // Messaging
    Route::match(['get', 'post'], 'inbox', 'InboxController@index');
    Route::post('messaging/booking/', 'InboxController@message');
    Route::post('messaging/reply/', 'InboxController@messageReply');
     
    Route::match(['get', 'post'], 'users/account-preferences', 'UserController@accountPreferences');
    Route::get('users/account_delete/{id}', 'UserController@accountDelete');
    Route::get('users/account_default/{id}', 'UserController@accountDefault');
    Route::get('users/transaction-history', 'UserController@transactionHistory');
    Route::post('users/account_transaction_history', 'UserController@getCompletedTransaction');
	// for customer payout settings
	Route::match(['GET', 'POST'], 'users/payout', 'PayoutController@index');
	Route::match(['GET', 'POST'], 'users/auth-payout', 'AuthPayoutController@index');
	Route::match(['GET', 'POST'], 'users/payout/setting', 'PayoutController@setting'); 
	Route::match(['GET', 'POST'], 'users/payout/edit-payout/', 'PayoutController@edit');
	Route::match(['GET', 'POST'], 'users/payout/delete-payout/{id}', 'PayoutController@delete');

	// for payout request
	Route::match(['GET', 'POST'], 'users/payout-list', 'PayoutController@payoutList');  
	Route::match(['GET', 'POST'], 'users/payout/success', 'PayoutController@success'); 

    Route::match(['get', 'post'], 'users/security', 'UserController@security');
    Route::get('logout', function()
	{
		Auth::logout(); 
		return Redirect::to('login');
	});
});

//for exporting iCalendar
Route::get('icalender/export/{id}', 'CalendarController@icalendarExport');
Route::post('admin/authenticate', 'Admin\AdminController@authenticate');
Route::post('duplicate-phone-number-check', 'UserController@duplicatePhoneNumberCheck');
Route::post('duplicate-phone-number-check-for-existing-customer', 'UserController@duplicatePhoneNumberCheckForExistingCustomer');
Route::match(['GET', 'POST'], 'admin/settings/sms', 'Admin\SettingsController@smsSettings');
Route::match(['get', 'post'],'upload_image','Admin\PagesController@uploadImage')->name('upload');


Route::post('/contact-us', 'HomeController@addContact')->name('addContact');

Route::get('svimport/{db}', 'HomeController@svimport');

Route::get('randam/{code}', 'HomeController@check_data');
Route::match(array('GET', 'POST'),'getproperty', 'HomeController@getproperty');

Route::group(['middleware' => ['locale']], function () { 
    Route::get('{name}', 'HomeController@staticPages');
});

