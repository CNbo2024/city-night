<!--================ Header Menu Area start =================-->
<?php 
    $lang = Session::get('language');
	$currentPaths= Route::getFacadeRoot()->current()->uri();
	if($currentPaths == 'old-home')
	{
	    $homepage_type = "old_home";
	}
?>

 <style>
   :root 
	{
        --primary-color: {{ $primary_color }};
    }
    
</style>

<link rel="stylesheet" type="text/css" href="{{ url('public/css/daterangepicker.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ url('public/css/glyphicon.css') }}"/>

<!--<input type="hidden" id="front_date_format_type" value="{{ Session::get('front_date_format_type')}}">-->
<input type="hidden" id="front_date_format_type" value="{{ $front_date_format_type }}">
<?php //demo site restore
/* if(config('global.demosite')=="yes") { ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script>
setInterval(function(){
const timeString = moment.utc(moment().endOf('hour').diff()).format('HH:mm:ss')
document.getElementById('timer').innerHTML = timeString;
}, 1000);
</script>
<div class="row sv_demo_mode">
	<div class="col-md-12">
		<span>Your Demo going to restore </span><span id="timer"></span>
		<a href="{{ url('svimport/db') }}"><span class="svrestore ml-4 rounded-4">Restore Now</span></a>
	</div>
</div>
<style>
.svrestore {
    background: #fff;
    color: red;
    font-size: 13px !important;
    padding: 0px 7px;
}
.sv_demo_mode {
   background: #ff0000;
    color: #fff;
    text-align: center;
    width: 100%;height:28px;
}
.header_area {
    top: 28px;
}
.sv_demo_mode span {
    font-size: 14px;
    font-weight: 600;
}
</style>
<?php } */ ?>	

<!-- //demo mode restore -->

<?php
        if(isset($_REQUEST['type']))
        {
            $type = $_REQUEST['type'];
            
            $start_date = $_REQUEST['checkin'];
            if($start_date!='')
            {
                 $start_date = $_REQUEST['checkin'];
            }
            else
            {
                $start_date = "";
            }
            
            if(isset($_REQUEST['checkout']))
            {
                $end_date = $_REQUEST['checkout'];
            }
            else
            {
                $end_date = "";
            }
            
            $month = date("M",strtotime($start_date));
            $day = date("d",strtotime($start_date));
            $day1 = date("d",strtotime($end_date));
            
            $month = date("M",strtotime($end_date));
            
            if($start_date!="")
            {
                $any_week = $day.'-'.$day1.' '.$month;
            }
            else
            {
                $any_week = ""; 
            }
        }
        else
        { 
            $type = "";
            $start_date = "";
            $end_date = "";
            $any_week ="";
        }
        if(isset($_REQUEST['location'])) { $location = $_REQUEST['location']; } else { $location =""; } 
        if(isset($_REQUEST['guest'])) { $guest = $_REQUEST['guest'].  trans('messages.home.guest'); } else { $guest =""; } 

        //echo $dlang = Session::get('home_type');
     ?>
     
     
<header class="header_area  animated fadeIn {{ $homepage_type }} <?php if($currentPaths=="index" or $currentPaths=="/" or $currentPaths =="old-home"){?>homenav<?php } else {?>migrateshop_othernav<?php } ?>">
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid container-fluid-100">
                <a class="navbar-brand logo_h d-none dark_logo" aria-label="logo" href="{{ url('/') }}"><img src="{{ $logo ?? '' }}" alt="logo" class="img-130x32"></a> 
            
               <a class="navbar-brand logo_h light_logo" aria-label="logo" href="{{ url('/') }}"><img src="{{ $light_logo ?? '' }}" alt="logo" class="img-130x32"></a> 

				<a class="navbar-brand logo_h d-block" aria-label="logo" href="{{ url('/') }}"><img src="{{ $favicon ?? '' }}" alt="logo" class="mob-logo"></a> 
                <a href="javascript:history.go(-1)" class="mob-back-btn d-block d-sm-none"><i class="fas fa-chevron-left"></i></a>
				<!-- Trigger Button -->
				<a href="#" aria-label="navbar" class="navbar-toggler" data-toggle="modal" data-target="#left_modal">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
                </a>
			
			    <?php //if($currentPaths != "properties/{slug}" && $currentPaths != "experiences/{slug}" && $currentPaths !='users/payout-list' && $currentPaths!='users/transaction-history') { ?>
                    @if(auth()->check())
                        @if(! auth()->user()->host)
            		        <form id="front-search-form1" method="post" action="{{url('search')}}" class="mob-search mt-3 mb-3 p-2">
            					{{ csrf_field() }}
            					<div class="row">
            						<input autocomplete="off" class="form-control p-3 text-14 m-0" id="location" placeholder="{{trans('messages.experience.anywhere')}}" name="location" type="text" value="@if(isset($location)){{$location}}@endif" required>
                					<input class="form-control p-3 text-14 m-0" id="any_week" placeholder="{{trans('messages.experience.any_week')}}" name="any_week" type="text" value="{{ $any_week }}">

            						<input autocomplete="off" class="form-control p-3 text-14 m-0" id="any_guest" placeholder="{{trans('messages.experience.add_guest')}}" name="any_guest" type="text" value="{{ $guest }}">
            					    <button type="submit" class="btn vbtn-default p-2 text-14"><i class="fa fa-search"></i></button>
            					</div>
            				</form>
                        @endif
                    @else
                        <form id="front-search-form1" method="post" action="{{url('search')}}" class="mob-search mt-3 mb-3 p-2">
                            {{ csrf_field() }}
                            <div class="row">
                                <input autocomplete="off" class="form-control p-3 text-14 m-0" id="location" placeholder="{{trans('messages.experience.anywhere')}}" name="location" type="text" value="@if(isset($location)){{$location}}@endif" required>
                                <input class="form-control p-3 text-14 m-0" id="any_week" placeholder="{{trans('messages.experience.any_week')}}" name="any_week" type="text" value="{{ $any_week }}">

                                <input autocomplete="off" class="form-control p-3 text-14 m-0" id="any_guest" placeholder="{{trans('messages.experience.add_guest')}}" name="any_guest" type="text" value="{{ $guest }}">
                                <button type="submit" class="btn vbtn-default p-2 text-14"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    @endif
				<?php //} ?>

					
                <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                    <div class="nav navbar-nav menu_nav justify-content-end">
  
                            <div class="nav-item">
                                	<div class="dropdown sv_user_login">

                                        @if(auth()->check())
                                            @if(! auth()->user()->host)                                	    
        										<button class="sv_become_host font-weight-600 mt-2" type="button" onclick="window.location.href='{{ auth()->check() ? '/hosts' : '/login' }}'"> 
        											{{trans('messages.header.host_mode')}}
        										</button>
                                            @endif

                                            @if(auth()->user()->host)                                     
                                                <button class="sv_become_host font-weight-600 mt-2" type="button" onclick="window.location.href='{{ '/property/create' }}'"> 
                                                    {{trans('messages.header.publish_a_property')}}
                                                </button>
                                            @endif
                                        @else
                                            <button class="sv_become_host font-weight-600 mt-2" type="button" onclick="window.location.href='{{ auth()->check() ? '/hosts' : '/login' }}'"> 
                                                {{trans('messages.header.become_a_host')}}
                                            </button>
                                        @endif
									</div>
									
                                    
                           </div>
						
						<div class="nav-item">
							<a class="nav-link globe" href="#" aria-label="modalLanguge" data-toggle="modal" data-target="#languageModalCenter"> <i class="fa fa-globe text-18"></i> </a>
                        </div>
                        
                        @if(!Auth::check())
                            <div class="nav-item">
									<div class="dropdown sv_user_login">
										<button class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-bars" aria-hidden="true"></i>
											<img src="{{ url('public/images/profile.jpg')}}" class="head_avatar" alt="">
										</button>
										
										<ul class="dropdown-menu">
										    <?php if(!request()->is('signup') && !request()->is('login')) { ?>
										        <li><a  aria-label="" data-toggle="modal" data-target="#registermodel"  href="#">{{trans('messages.sign_up.sign_up')}}</a></li>
                                            <?php } else { ?>
										        <li><a  href="{{ url('signup') }}">{{trans('messages.sign_up.sign_up')}}</a></li>
										    <?php } ?>
										    
										  <?php if(!request()->is('login') && !request()->is('signup')) { ?>
  										      <li><a aria-label="" data-toggle="modal" data-target="#loginmodel"  href="#">{{trans('messages.header.login')}}</a></li>
                                            <?php } else { ?>
										        <li><a  href="{{ url('login') }}">{{trans('messages.header.login')}}</a></li>
										    <?php } ?>
										    
										  <hr>
										  <li><a href="{{url('help')}}">{{trans('messages.header.help')}}</a></li>
										</ul>
									</div>
								
                            </div>
                          
                        @else
                        
                        <?php
                            $msgcount = App\Models\Messages::Where('receiver_id', Auth::id())
                                            ->where('read', '0')
                                            ->orderBy('id', 'desc')->get()
                                            ->unique('booking_id'); 
                                                        
                            $mcount=  count($msgcount);              
                        ?>
							
						 <div class="nav-item">
									<div class="dropdown sv_user_login">
										<button class="dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-bars" aria-hidden="true"></i>
											<img src="{{Auth::user()->profile_src}}" class="head_avatar" alt="{{Auth::user()->first_name}}">
										@if($mcount!='0')<span class="text-10 mcount"><i class="fa fa-circle svred" aria-hidden="true"></i></span>@endif
										</button>
										
										<ul class="dropdown-menu">
                                            @if(auth()->user()->host)
                                                <a class="text-color" href="{{ url('hosts') }}">
                                                    <li class="vbg-default-hover border-0  {{ (request()->is('hosts')) ? 'active-sidebar' : '' }}">
                                                        {{trans('messages.header.dashboard')}}
                                                    </li>
                                                </a>

                                                <a class="text-color" href="{{ url('calendar') }}">
                                                    <li class="vbg-default-hover border-0  {{ (request()->is('calendar')) ? 'active-sidebar' : '' }}">
                                                        {{trans('messages.calendar')}}
                                                    </li>
                                                </a>

                                                <a class="text-color" href="{{ url('property/create') }}">
                                                    <li class="vbg-default-hover border-0 {{ (request()->is('property/create')) ? 'active-sidebar' : '' }}">
                                                        {{trans('messages.header.publish_a_property')}}
                                                    </li>
                                                </a>

                                                <a class="text-color" href="{{ url('properties') }}">
                                                    <li class="vbg-default-hover border-0 {{ (request()->is('properties')) ? 'active-sidebar' : '' }}">
                                                        {{trans('messages.sidenav.my_listing')}}
                                                    </li>
                                                </a>

                                                <a class="text-color" href="{{ url('my-bookings') }}">
                                                    <li class="vbg-default-hover border-0 {{ (request()->is('my-bookings')) ? 'active-sidebar' : '' }}">
                                                        {{trans('messages.header.my_booking')}}
                                                    </li>
                                                </a>

                                                <a class="text-color" href="{{ url('inbox') }}">
                                                    <li class="vbg-default-hover border-0 {{ (request()->is('inbox')) ? 'active-sidebar' : '' }}">
                                                        {{trans('messages.header.inbox')}} <span class="badge badge-color">{{ $mcount }}</span>
                                                    </li>
                                                </a>
                                                
                                                <a class="text-color" href="{{ url('users/payout-list') }}">
                                                    <li class="vbg-default-hover border-0  {{ (request()->is('users/payout-list' ) || request()->is('users/payout')) ? 'active-sidebar' : '' }}">
                                                        {{trans('messages.sidenav.payment_account')}}
                                                    </li>
                                                </a>

                                                <a class="text-color" href="{{ url('dashboard') }}">
                                                    <li class="vbg-default-hover border-0  {{ request()->is('dashboard' ) ? 'active-sidebar' : '' }}">
                                                        {{trans('messages.switch_to_traveler_mode')}}
                                                    </li>
                                                </a>
                                            @endif

                                            @if(! auth()->user()->host)
    										    <a class="text-color" href="{{ url('dashboard') }}">
                                					<li class="vbg-default-hover border-0  {{ (request()->is('dashboard')) ? 'active-sidebar' : '' }}">
                                						{{trans('messages.header.dashboard')}}
                                					</li>
                                				</a>
                                				<a class="text-color" href="{{ url('users/profile') }}">
                                					<li class="vbg-default-hover border-0 {{ (request()->is('users/profile') || request()->is('users/profile/media') || request()->is('users/edit-verification') || request()->is('users/security')) ? 'active-sidebar' : '' }}">
                                						{{trans('messages.utility.profile')}}
                                					</li>
                                				</a>

    											@if($enable_experience == "Yes")
                                				<a class="text-color" href="{{ url('experience') }}">
                                					<li class="vbg-default-hover border-0 {{ (request()->is('experience')) ? 'active-sidebar' : '' }}">
                                						{{trans('messages.experience.my_experience')}}
                                					</li>
                                				</a>
                                				@endif
                                				
                                				<a class="text-color" href="{{ url('trips/active') }}">
                                					<li class="vbg-default-hover border-0 {{ (request()->is('trips/active')) ? 'active-sidebar' : '' }}">
                                						{{trans('messages.header.your_trip')}}
                                					</li>
                                				</a>
                                				
                                				<a class="text-color" href="{{ url('mywishlist') }}">
                                					<li class="vbg-default-hover border-0 {{ (request()->is('mywishlist')) ? 'active-sidebar' : '' }}">
                                						{{trans('messages.utility.wishlist')}}
                                					</li>
                                				</a>
                                    
                                				<a class="text-color" href="{{ url('inbox') }}">
                                					<li class="vbg-default-hover border-0 {{ (request()->is('inbox')) ? 'active-sidebar' : '' }}">
                                						{{trans('messages.header.inbox')}} <span class="badge badge-color">{{ $mcount }}</span>
                                					</li>
                                				</a>
                                				
                                				<a class="text-color" href="{{ url('users/payout-list') }}">
                                					<li class="vbg-default-hover border-0  {{ (request()->is('users/payout-list' ) || request()->is('users/payout')) ? 'active-sidebar' : '' }}">
                                						{{trans('messages.sidenav.payment_account')}}
                                					</li>
                                				</a>
                                            @endif
                                            
                                            
										    <hr>
										  <li> <a class="" href="{{ url('logout') }}" aria-label="logout">{{trans('messages.header.logout')}}</a></li>
										</ul>
									</div>
								
                            </div>
			            

                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </div> 
	

<?php  //if($currentPaths != "properties/{slug}" && $currentPaths != "experiences/{slug}" && $currentPaths !='users/payout-list' && $currentPaths!='users/transaction-history' ) { ?>
<?php if($homepage_type != 'old_home' || $currentPaths!="/" && $currentPaths != "old-home" ) { ?>
<section class="sub_header col-md-6 p-0" style="display:none">
    <div class="align-items-center text-center text-md-left svmobsearch desk-search-form container">
                      
           <div class="container"> 
              @if($enable_experience == "Yes")
              <ul class="nav text-center" id="myTab" role="tablist">
                <li class="nav-item waves-effect waves-light">
                  <a class="nav-link <?php if($type=="property" || $type == "") { ?>active<?php } ?>" id="property-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">{{trans('messages.home.places_to_stay')}}</a>
                </li>
                <li class="nav-item waves-effect waves-light">
                  <a class="nav-link <?php if($type=="experience") { ?>active<?php } ?>" id="exp-tab" data-toggle="tab" href="#home" role="tab" aria-controls="profile" aria-selected="false">{{trans('messages.home.experience')}}</a>
                </li>
              </ul>
              @endif
              
              <div class="tab-content pt-5" id="myTabContent">
                <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <div class="row mb-5">
                          
                          <div class="main_formbg item animated zoomIn mob-form-bg">

                            <form id="front-search-form-home" method="post" action="{{url('search')}}">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-4 exp_location">
                                        <label>{{trans('messages.search.location')}}</label>
                                        <input class="form-control p-3 text-14" id="front-search-field" placeholder="{{trans('messages.home.where_want_to_go')}}" autocomplete="off" name="location" type="text" value="{{ $location }}" >
                                    </div>
                                    <input type="hidden" id="type" name="type" value="<?php if($type=="") { ?>property<?php } else { echo $type; } ?>">
                                    <div class="col-md-4 nopadding exp_dates">
                                        <div class="row" id="daterange-btn5">
                                            <div class="col-md-6 col-6 mt-4 mt-md-0 mob-pd-0 p-0">
                                                <label>{{trans('messages.search.check_in')}}</label>
                                                <input class="form-control p-3  text-14 checkinout" name="checkin" id="startDate_home" type="text" placeholder="{{trans('messages.search.add_dates')}}" value="{{ $start_date }}" >
                                            </div>
                                            <div class="col-md-6 col-6 mt-4 mt-md-0 p-0 mob-pd-0 pl-4">
                                                <label>{{trans('messages.search.check_out')}}</label>
                                                <input class="form-control p-3 text-14 checkinout" name="checkout" id="endDate_home" placeholder="{{trans('messages.search.add_dates')}}" type="text" value="{{ $end_date }}" >
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 border-right-0 mt-4 mt-md-0" id="svguest">
                                        <label>{{trans('messages.home.guest')}}</label>
                                            <select id="front-search-guests" class="form-control  text-14">
                                                <option class="p-4 text-14" value="1">1 {{trans('messages.home.guest')}}</option>
                                                @for($i=2;$i<=16;$i++)
                                                    <option <?php if($guest==$i) { echo "selected"; } ?>  class="p-4 text-14" value="{{ $i }}"> {{ ($i == '16') ? $i.'+ '.trans('messages.home.guest') : $i.' '.trans('messages.property_single.guest') }} </option>
                                                @endfor
                                            </select>
                                    </div>

                                    <div class="col-md-1 front-search mt-2 border-right-0 d-none d-sm-block">
                                        <button type="submit" class="btn vbtn-default btn-block p-3 text-16"><i class="fa fa-search"></i></button>
                                    </div>
                                    <div class="col-12 d-block d-sm-none front-search mt-2">
                                        <button type="submit" class="btn vbtn-default btn-block p-3 text-16"><i class="fa fa-search"></i> {{trans('messages.home.search')}}</button>
                                        <span class="sv_close_butt btn vbtn-primary btn-block p-3 text-13 d-block d-sm-none"><i class="fa fa-times"></i> Close</span>

                                    </div>
                                </div>
                            </form>
                            </div>
                    </div><!-- form end -->
                 </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    
                </div>
              </div>
            </div>
                    
    </div>
</section>
<?php } ?>
<?php  //} ?>

</header>

<!-- Modal Window -->
<div class="modal right fade" id="left_modal" tabindex="-1" role="dialog" aria-labelledby="left_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 secondary-bg"> 
                @if(Auth::check())
                    <div class="row justify-content-center align-items-center">
                        <div>
                            <img src="{{Auth::user()->profile_src}}" class="head_avatar" alt="{{Auth::user()->first_name}}">
                        </div>

                        <div>
                            <p class="text-white mt-4 mb-0"> @if(Auth::user()->display_name=="") {{Auth::user()->first_name}} @else {{Auth::user()->display_name}} @endif</p>
                            <a class="text-white text-14" href="{{ url('dashboard') }}">{{trans('messages.header.show_profile')}}</a>
                        </div>
                    </div>
                @endif

                <button type="button" class="close text-28" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
			</div>
			
            <div class="modal-body p-0">
                <ul class="mobile-side">
                    @if(Auth::check())
                        <li><a href="{{ url('dashboard') }}">{{trans('messages.header.dashboard')}}</a></li>
                        <li><a href="{{ url('users/profile') }}">{{trans('messages.utility.profile')}}</a></li>
                        
                        <li><a href="{{ url('property/create') }}">{{trans('messages.header.list_space')}}</a></li>
                        <li><a href="{{ url('experience/create') }}">{{trans('messages.experience.add_experience')}}</a></li>
                        
                        <li><a href="{{ url('properties') }}">{{trans('messages.sidenav.my_listing')}}</a></li>
                         <li><a href="{{ url('experience') }}">{{trans('messages.experience.my_experience')}}</a></li>
                        <li><a href="{{ url('my-bookings') }}">{{trans('messages.header.my_booking')}}</a></li>
                        <li><a href="{{ url('trips/active') }}">{{trans('messages.header.your_trip')}}</a></li>
                        <li><a href="{{ url('mywishlist') }}">{{trans('messages.utility.wishlist')}}</a></li>

                        <li><a href="{{ url('inbox') }}">{{trans('messages.header.inbox')}} &nbsp;<span class="badge badge-color">{{ $mcount }}</span></a></li>

                        <li><a href="{{ url('dashboard') }}">{{trans('messages.switch_to_traveler_mode')}}</a></li>
                        
                        <li><a href="{{ url('users/payout-list') }}">{{trans('messages.sidenav.payment_account')}}</a></li>
                      
                        <li><a href="{{ url('logout') }}">{{trans('messages.header.logout')}}</a></li>
                    @else
                        <li><a href="{{ url('/') }}">{{trans('messages.home.home')}}</a></li>
                        <li><a href="{{ url('become-host') }}">{{trans('messages.header.become_a_host')}}</a></li>
                        <li><a href="{{ url('help') }}">{{trans('messages.header.help')}}</a></li>
                        <li><a href="{{ url('signup') }}">{{trans('messages.sign_up.sign_up')}}</a></li>
                        <li><a href="{{ url('login') }}">{{trans('messages.header.login')}}</a></li>
                    @endif
	                    <li>
	                        <a href="#" aria-label="modalLanguge" data-toggle="modal" data-target="#languageModalCenter"> <i class="fa fa-globe"></i> <u>{{  Session::get('language_name')  ?? $default_language[0]->name }} </u></a>
							<a href="#" aria-label="modalCurrency" data-toggle="modal" data-target="#currencyModalCenter"> <span class="ml-4">{!! Session::get('symbol')  !!} - <u>{{ Session::get('currency')  }}</u> </span></a>
						</li>
                    @if(Request::segment(1) != 'help')
                    <li>
                        <a class="mt-3" href="{{ url('property/create') }}">
                            <button class="btn vbtn-outline-success text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3">
                                    {{trans('messages.header.list_space')}}
                            </button>
                        </a>
                         <a class="mt-3" href="{{ url('experience/create') }}">
                            <button class="btn vbtn-outline-success text-14 font-weight-700 pl-5 pr-5 pt-3 pb-3">
                                    {{trans('messages.experience.add_experience')}}
                            </button>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
<!--================Header Menu Area =================-->

<script>
    function goBack() {
  window.history.back();
}

</script>


<!-- login model -->
<?php if(!request()->is('login')) { ?>

<div class="modal fade" id="loginmodel" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        
      <div class="modal-header">
		<h3 class="text-center">{{trans('messages.login.login')}}</h3>
        <button type="button" class="close closeLight position-relative" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  	<div class="d-flex justify-content-center p-5">
		<div class=" mb-5" >
			@if(Session::has('message'))
				<div class="row mt-3">
					<div class="col-md-12 p-2 text-center text-14 alert {{ Session::get('alert-class') }} alert-dismissable fade in opacity-1">
						<a href="#"  class="close text-18" data-dismiss="alert" aria-label="close">&times;</a>
						{{ Session::get('message') }}
					</div>
				</div>
			@endif 
			
			<form id="login_form" method="post" action="{{url('authenticate')}}"  accept-charset='UTF-8'>  
				{{ csrf_field() }}
				<div class="form-group col-sm-12 p-0">
					@if ($errors->has('email'))
						<p class="error">{{ $errors->first('email') }}</p> 
					@endif
					<input type="email" class="form-control text-14" value="{{ old('email') }}" name="email" placeholder = "{{trans('messages.login.email')}}">
				</div>

				<div class="form-group col-sm-12 p-0">
					@if ($errors->has('password')) 
						<p class="error">{{ $errors->first('password') }}</p> 
					@endif

                    <div class="input-group">
                        <input type="password" class="form-control text-14" value="" name="password" placeholder = "{{trans('messages.login.password')}}">

                        <div class="input-group-append">
                            <button tpye="button" class="btn btn-outline-secondary">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>                    
				</div>
				
				@if($enable_captcha=="yes")
				
				{!! NoCaptcha::renderJs() !!}

								<div class="form-group col-md-12 p-0 mt-4 {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
									{!! app('captcha')->display() !!}
									@if ($errors->has('g-recaptcha-response'))
										<span class="help-block text-danger">
											<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
										</span>
									@endif
								</div>
				@endif

				<div class="form-group col-sm-12 p-0 mt-3" >
					<div class="d-flex justify-content-between">
						<div class="m-3 text-14">
							<input type="checkbox" class='remember_me' id="remember_me2" name="remember_me" value="1">
							{{trans('messages.login.remember_me')}}
						</div>
						
						<div class="m-3 text-14">
							<a href="#" class="forgot-password text-right" aria-label="" data-toggle="modal" data-target="#forgotmodel" data-dismiss="modal">{{trans('messages.login.forgot_pwd')}}</a>
						</div>
					</div>
				</div>

				<div class="form-group col-sm-12 p-0" >
					<button type='submit' id="" class="btn pb-3 pt-3  button-reactangular text-15 vbtn-success w-100 rounded"> <i class="spinner fa fa-spinner fa-spin d-none" ></i>
						<span id="btn_next-text" class="font-weight-700">{{trans('messages.login.login')}}</span>
					</button>
				</div>
			</form>
			
				
							<div id="sv_phone_div" class="hide">
							
							 <form class="" id="otp_form" method="post" action="{{url('sendotp')}}"  accept-charset='UTF-8'>  
									{{ csrf_field() }}
									
									<div class="form-group col-sm-12 p-0" id="svpno">
										<label class="font-weight-600">{{trans('messages.users_profile.phone')}}</label>
										<input type="tel" class="form-control text-14" id="pno" name="pno" required value="">
									</div>
								    <input type="hidden" name="default_country" id="defaultcountry" class="form-control">
									<input type="hidden" name="carrier_code1" id="carrier_code1" class="form-control">
									<input type="hidden" name="formatted_phone" id="formattedphone" class="form-control">

									<div class="form-input svotp" style="display:none">
										<input type="text" class="form-control" id="otp" name="otp" placeholder="{{trans('messages.users_profile.otp')}}" >
										<i class="icofont-phone"></i>
									</div>
								  
									<button type='' id="svsendotp" class="btn pb-3 mt-3 pt-3 text-15 rounded-3 vbtn-success w-100 rounded"> <i class="spinner fa fa-spinner fa-spin d-none" ></i>
										<span id="btn_next-text">{{trans('messages.login.login')}} </span>
									</button>
							</form>
							
							</div>
							
									
			<div class="mt-3 text-14">
				{{trans('messages.login.do_not_have_an_account')}}
				<a class="text-danger" id="svregmodal" aria-label="" data-toggle="modal" data-target="#registermodel" data-dismiss="modal" href="#" >
				{{trans('messages.login.register')}}
				</a>
			</div>  
			
			<p class="text-center font-weight-700 mt-4">{{trans('messages.login.or')}}</p>
			
			@if($enable_facebook=="yes")
			<a href="{{ isset($facebook_url) ? $facebook_url:URL::to('facebookLogin') }}">
				<button class="btn btn-outline-primary pt-3 pb-3 text-16 w-100">
					<span><i class="fab fa-facebook-f mr-2 text-16"></i> {{trans('messages.sign_up.sign_up_with_facebook')}}</span>
				</button>
			</a>
			@endif
			
			@if($enable_google=="yes")
			<a href="{{URL::to('googleLogin')}}">
				<button class="btn btn-outline-danger pt-3 pb-3 text-16 w-100 mt-3">
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
<?php } ?>


<?php if(!request()->is('signup')) { ?>

<!-- Register modal -->
<?php
$url = URL::to("/");
 ?>
<div class="modal fade" id="registermodel" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	
	 <div class="modal-header">
		<h3 class="text-center">	{{trans('messages.login.register')}}</h3>
        <button type="button" class="close closeLight position-relative" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  
      <div class="modal-body">
	  	<div class="d-flex justify-content-center">
		
		<div class="p-5 mb-5">
				<form id="signup_form" name="signup_form" method="post" action="{{url('create')}}" class='signup-form login-form' accept-charset='UTF-8' onsubmit="return ageValidate();">    
					{{ csrf_field() }}
					<div class="row text-16">
						<input type="hidden" name='email_signup' id='form'>
						<input type="hidden" name="default_country" id="default_country" class="form-control">
						<input type="hidden" name="carrier_code" id="carrier_code" class="form-control">
						@if(!Auth::check())
						<input type="hidden" name="formatted_phone" id="formatted_phone" class="form-control">
						@endif
						<div class="form-group col-sm-12 p-0">
							@if ($errors->has('first_name')) <p class="error-tag">{{ $errors->first('first_name') }}</p> @endif
							<input type="text" class='form-control text-14 p-2' value="{{ old('first_name') }}" name='first_name' id='first_name' placeholder='{{ trans('messages.sign_up.first_name') }}'>
						</div>

						<div class="form-group col-sm-12 p-0">
								@if ( $errors->has('last_name') ) <p class="error-tag">{{ $errors->first('last_name') }}</p> @endif
								<input type="text" class='form-control text-14 p-2' value="{{ old('last_name') }}" name='last_name' id='last_name' placeholder='{{ trans('messages.sign_up.last_name') }}'>
						</div>

						<div class="form-group col-sm-12 p-0">
								<input type="text" class='form-control text-14 p-2' value="{{old('email')}}" name='email' id='email' placeholder='{{ trans('messages.login.email') }}'>
								@if ($errors->has('email')) 
									<p class="error-tag">
									{{ $errors->first('email') }}
									</p> 
								@endif
								<div id="emailError"></div>
						</div>
						
						@if(!Auth::check())
						<div class="form-group col-sm-12 p-0">
								<input type="tel" class="form-control text-14 p-2" id="phone" name="phone" placeholder="111-111-1111">
						</div>
						@endif



						<div class="form-group col-sm-12 p-0">
                            @if ( $errors->has('password') ) <p class="error-tag">{{ $errors->first('password') }}</p> @endif

                            <div class="input-group">
                                <input type="password" class="form-control password-input text-14 p-2" name="password" id="password" placeholder='{{ trans('messages.login.password') }}'>

                                <div class="input-group-append">
                                    <button type="button" class="view-password btn btn-secondary p-2">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
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

						<button type='submit'  class="btn pb-3 pt-3 text-15 button-reactangular vbtn-success w-100 ml-0 mr-0 mb-3"> <i class="spinner fa fa-spinner fa-spin d-none" ></i>
							<span id="btn_next-text" class="font-weight-700">{{ trans('messages.sign_up.sign_up') }}</span>
						</button>
					</div>
				</form>
				<div class="text-14">
					{{trans('messages.sign_up.already')}} {{ $site_name }} {{ trans('messages.sign_up.member') }}?
					<a href="#" aria-label="" data-toggle="modal" data-target="#loginmodel" id="svloginmodal" data-dismiss="modal">
					{{trans('messages.sign_up.login')}}
					</a>
				</div>
				<p class="text-center font-weight-700 mt-4">{{trans('messages.login.or')}}</p>

                @if($enable_facebook=="yes")
				<a href="{{ isset( $facebook_url ) ? $facebook_url:URL::to('facebookLogin') }}">
					<button class="btn btn-outline-primary pt-3 pb-3 text-16 w-100">
						<span><i class="fab fa-facebook-f mr-2 text-16"></i> {{trans('messages.sign_up.sign_up_with_facebook')}}</span>
					</button>
				</a>
				@endif

                @if($enable_google=="yes")
				<a href="{{URL::to('googleLogin')}}">
						<button class="btn btn-outline-danger pt-3 pb-3 text-16 w-100 mt-3">
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
</div>
<?php } ?>


<?php if(!request()->is('forgot_password')) { ?>

<div class="modal fade" id="forgotmodel" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        
      <div class="modal-header">
		  <h3 class="text-center">{{trans('messages.forgot_pass.reset_pass')}}</h3>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			   <span aria-hidden="true">&times;</span>
			</button>
       </div>
      <div class="modal-body">
			
          @if(Session::has('message'))
    		 <div class="row mt-5">
    		 	 <div class="col-md-12 text-13  alert {{ Session::get('alert-class') }} alert-dismissable fade in opacity-1">
    			 	 <a href="#"  class="close " data-dismiss="alert" aria-label="close">&times;</a>
    					{{ Session::get('message') }}
    			 </div>
    		 </div>
		  @endif 
              <div class="mt-5" >
                  <div class="col-md-12">
                     	<form id="forgot_password_form" method="post" action="{{url('forgot_password')}}" class='signup-form login-form mt-3' accept-charset='UTF-8'>  
				             {{ csrf_field() }}
				            <div class="form-group col-sm-12 p-0 mt-3">
    			               <label class="font-weight-600">{{trans('messages.forgot_pass.please_enter_email')}} </label>
				              	<input type="text" id="email" class="form-control" name="email" placeholder = "">
					            @if ($errors->has('email'))<label class="text-danger email-error">{{ $errors->first('email') }}</label>@endif
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
    				             <button id="reset_btn" class="btn pb-3 pt-3 text-15 button-reactangular vbtn-success w-100 rounded" type="submit" > <i class="spinner fa fa-spinner fa-spin d-none" ></i>
							        <span id="btn_next-text" class="font-weight-700">{{trans('messages.forgot_pass.reset_password')}}</span>
						          </button>
				            </div>
			             </form>
                     </div>
               </div>
      
      </div>
   
    </div>
  </div>
</div>
<?php } ?>

<div class="overlay" style="display:none"></div>


@section('validation_script')
<?php if(!request()->is('login') && !request()->is('signup')) { ?>

<script type="text/javascript">
    @if( !Auth::check() )
       @if (count($errors) > 0)
           $("#loginmodel").modal('show');
        @endif  
    @endif  
</script>

<script type="text/javascript">
jQuery.validator.addMethod("laxEmail", function(value, element) {
	return this.optional( element ) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value );
}, "{{ __('messages.jquery_validation.email') }}" );


$(document).ready(function () {
    $('.view-password').click(function () {

                if ($('[name=password]').attr('type') == 'password') {
                    $('[name=password]').attr('type', 'text');
                } else {
                    $('[name=password]').attr('type', 'password');
                }
            });

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
			$('#defaultcountry').val(countryData.iso2);
			$('#carrier_code1').val(countryData.dialCode);

			$("#pno").on("countrychange", function(e, countryData)
			{
				formattedPhone1();
				// log(countryData);
				$('#defaultcountry').val(countryData.iso2);
				$('#carrier_code1').val(countryData.dialCode);
				if ($.trim($(this).val()) !== '') {
					//Invalid Number Validation - Add
					if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
						$('#tel-error').addClass('error').html('{{trans("messages.experience.enter_a_valid_pno")}}').css("font-weight", "bold");
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
			$("input[name=pno]").on('blur keyup', function(e)
			{
				formattedPhone1();
				$('#btn').attr('disabled', false);
				$('#pno').html('').css("border-color","none");
				if ($.trim($(this).val()) !== '') {
					if (!$(this).intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($(this).val()))) {
						$('#tel-error').addClass('error').html('{{trans("messages.experience.enter_a_valid_pno")}}').css("font-weight", "bold");
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

		 function formattedPhone1()
		{
			if ($('#pno').val != '') {
				var p = $('#pno').intlTelInput("getNumber").replace(/-|\s/g,"");
				$("#formattedphone").val(p);
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

<?php } ?>


<?php if(!request()->is('forgot_password')) { ?>
<script type="text/javascript">
	jQuery.validator.addMethod("laxEmail", function(value, element) {
			// allow any non-whitespace characters as the host part
			return this.optional( element ) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value );
		}, "{{ __('messages.jquery_validation.email') }}" );

	$(document).ready(function () {
		
	$("#reset_btn").on("click", function (e)
    {	
    	$(".email-error").hide();
    });

    $('#forgot_password_form').validate({
        rules: {
			email: {
				required: true,
				maxlength: 255,
				laxEmail: true
			}
        },
        submitHandler: function(form)
        {
     		$("#reset_btn").on("click", function (e)
            {	
            	$("#reset_btn").attr("disabled", true);
                e.preventDefault();
            });
            
            $(".spinner").removeClass('d-none');
            $("#btn_next-text").text("{{trans('messages.forgot_pass.reset_link')}}..");
            return true;
        },
        messages: {
		email: {
				required:  "{{ __('messages.jquery_validation.required') }}",
				maxlength: "{{ __('messages.jquery_validation.maxlength255') }}",
            }
        }
    });
});
</script>

<?php } ?>


<script type="text/javascript">
 $(document).on('keyup', '#front-search-field1', function(){
  autocomplete = new google.maps.places.Autocomplete(document.getElementById("front-search-field1")); 
});

 $("#front-search-form1").submit(function(e) {
  e.preventDefault()
    var t = "",
        a = "",
        o = "1",
        i = "";
       var n = $("#front-search-field1").val(),   
        c = n.replace(" ", "+");
        var type = $("#sv_header_property_type").val();
    if(type=="property")
    {
        window.location.href = APP_URL + "/search?location=" + c + "&checkin=" + t + "&checkout=" + a + "&guest=" + o + "&type=" + type, e.preventDefault()
    }
    else
    {
        window.location.href = APP_URL + "/expsearch?location=" + c + "&checkin=" + t + "&checkout=" + a + "&guest=" + o + "&type=" + type, e.preventDefault()
    }
}); 

/*
$(document).ready(function() 
{
    $('.mob-search').click(function() {
        $('.svmobsearch').toggle();
    });
}); */


(function () {
    "use strict";

    var cookieAlert = document.querySelector(".cookiealert");
    var acceptCookies = document.querySelector(".acceptcookies");

    if (!cookieAlert) {
        return;
    }

    cookieAlert.offsetHeight; // Force browser to trigger reflow (https://stackoverflow.com/a/39451131)

    // Show the alert if we cant find the "acceptCookies" cookie
    if (!getCookie("acceptCookies")) {
        cookieAlert.classList.add("show");
    }

    // When clicking on the agree button, create a 1 year
    // cookie to remember user's choice and close the banner
    acceptCookies.addEventListener("click", function () {
        setCookie("acceptCookies", true, 365);
        cookieAlert.classList.remove("show");

        // dispatch the accept event
        window.dispatchEvent(new Event("cookieAlertAccept"))
    });

    // Cookie functions from w3schools
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
})();


$('body').on('click', '#exp-tab', function() {
    $('#svguest').hide();
    $('.exp_location').removeClass('col-md-4');
    $('.exp_location').addClass('col-md-5');
    
    $('.exp_dates').removeClass('col-md-4');
    $('.exp_dates').addClass('col-md-6');
    $('#type').val('experience');
    $("#front-search-field").focus();
    //$('.flexible-btn').hide();
}); 

$('body').on('click', '#property-tab', function() {
    $('#svguest').show();
     $('.exp_location').removeClass('col-md-5');
    $('.exp_location').addClass('col-md-4');
    
    $('.exp_dates').removeClass('col-md-6');
    $('.exp_dates').addClass('col-md-4');
    $('#type').val('property');
    $("#front-search-field").focus();
    //$('.flexible-btn').show();

}); 


$('body').on('click', '.sv_close_butt', function() {
    $('#front-search-form1').show();
    $('.sub_header').hide();
    $('.sv_search_page ').removeClass('overlay');
    $('header ').removeClass('sv_minheight');
}); 
</script>

    <script type="text/javascript" src="{{ url('public/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/js/daterangepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{ url('public/js/front.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('public/js/daterangecustom.js')}}"></script>
    <script type="text/javascript">
        /* $(function() 
        {
            dateRangeBtn(moment(),moment());
        }); */
    </script>
    
    <script type="text/javascript">
       $(function() {
           var checkin = $('#startDate_home').val();
           var checkout = $('#endDate_home').val();
           var page = '';
           homedateRangeBtn(checkin,checkout,page);
           
       });

</script>
 
<?php if($homepage_type == 'old_home' && $currentPaths=="/" || $currentPaths=="old-home" ) { ?>
<script type="text/javascript">
 $('#location, .any_guest, #front-search-form1').click(function(e)
    {   
        window.scrollTo({ top: 0, behavior: 'smooth' });
        e.preventDefault();
    });
</script>
<?php } else { ?>

<script type="text/javascript">
 $('#location, .any_guest, #front-search-form1').click(function(e)
    {
        $('#front-search-form1').hide();
        $('.sub_header').show();

        $('header').addClass('sv_minheight');
        window.scrollTo({ top: 0, behavior: 'smooth' });
        $("body").css("overflow", "hidden");
        $('.overlay').fadeIn(300);
        $("#front-search-field").focus();
        e.preventDefault();
    });

    $(document).on('click','.overlay',function() 
    {
        $(this).fadeOut('3000');
        $('.migrateshop_othernav #front-search-form1').show();
        $('.sub_header').hide();
        $('header ').removeClass('sv_minheight');
        $("body").css("overflow", "scroll");
        $('#front-search-form1').show();
    });
</script>
<?php } ?>    

<style>
   #payment-form .ElementsApp input{
    border:1px solid #eee !important;
}
</style>

@endsection
          
   

