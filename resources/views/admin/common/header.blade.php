<header class="main-header">
	<a href="{{URL::to('admin/dashboard')}}" class="logo">
		@if (!empty($logo))
			<span class="logo-mini"><img class="admin-fav-icon" src="{{ $favicon ?? '' }}" /></span>
		@endif
		
		@if (!empty($logo))
			<span class="logo-lg"><img src="{{ $logo ?? '' }}" /></span>
		@endif
	</a>

	<nav class="navbar navbar-static-top header_controls">
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
			    <li><span><div id="google_translate_element" style="padding-top:15px;padding-bottom:15px"></div></span></li>
			    <li class="hidden-xs"><a href="{{URL::to('/')}}" target="_blank">Goto Main Site </a></li>
				<li class="dropdown user user-menu">
				    
				   <?php if(config('global.demosite')=="yes") { ?>
    					<a href="#">
    						<img src="{{\Auth::guard('admin')->user()->profile_src}}" class="user-image" alt="User Image">
    						<span class="hidden-xs">{{ ucfirst(Auth::guard('admin')->user()->username) }}</span>
    					</a>    
    			    <?php } else { ?>
    			        <a href="{{URL::to('/')}}/admin/profile">
    						<img src="{{\Auth::guard('admin')->user()->profile_src}}" class="user-image" alt="User Image">
    						<span class="hidden-xs">{{ ucfirst(Auth::guard('admin')->user()->username) }}</span>
    					</a> 
					<?php } ?>
				<!--	<ul class="dropdown-menu">
						<li class="user-header">
							<img src="{{\Auth::guard('admin')->user()->profile_src}}" class="img-circle" alt="User Image">
							<p>
								{{ ucfirst(Auth::guard('admin')->user()->username) }}
								<small>Member since {{ date('M, Y', strtotime(Auth::guard('admin')->user()->created_at)) }}</small>
							</p>
						</li>
				
						<li class="user-footer">
							<div class="pull-left">
							<a href="{{URL::to('/')}}/admin/profile" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
							<a href="{{URL::to('/')}}/admin/logout" class="btn btn-primary btn-flat">Sign out</a>
							</div>
						</li>
					</ul>-->
					<li><a href="{{URL::to('/')}}/admin/logout"><span class="hidden-xs">Logout</span> <span class="visible-xs"><i class="fa fa-sign-out"></i></span></a></a></li>
				</li>
			</ul>
		</div>
	</nav>
</header>

<div class="flash-container">
	@if(Session::has('message'))
		<div class="alert {{ Session::get('alert-class') }} text-center mb-0" role="alert">
			{{ Session::get('message') }}
			<a href="#" class="pull-right" class="alert-close" data-dismiss="alert">&times;</a>
		</div>
	@endif
	
	<div class="alert alert-success text-center mb-0 d-none" id="success_message_div" role="alert">
		<a href="#" class="pull-right" class="alert-close" data-dismiss="alert">&times;</a>
		<p id="success_message"></p>
	</div>

	<div class="alert alert-danger text-center mb-0 d-none" id="error_message_div" role="alert">
		<p><a href="#" class="pull-right" class="alert-close" data-dismiss="alert">&times;</a></p>
		<p id="error_message"></p>
	</div>
</div>

<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: '', autoDisplay: false}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>



