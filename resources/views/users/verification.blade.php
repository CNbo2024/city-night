	@extends('template')
	@section('main')
	<div class="margin-top-85">
		<div class="row m-0">
			<!-- sidebar start-->
			@include('users.sidebar')
			<!--sidebar end-->
			<div class="col-lg-12 p-0 mt-5">
				<div class="container-fluid container-fluid-90">
					<div class="col-md-12">
						<div class="row">
						
						<div class="col-md-3 mt-5"> 
						    @include('users.profile_nav')
						</div>
						
						 <div class="col-md-9 mb-5"> 
						<!--Success Message -->
						@if(Session::has('message'))
							<div class="row mt-5">
								<div class="col-md-12  alert {{ Session::get('alert-class') }} alert-dismissable fade in top-message-text opacity-1">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									{{ Session::get('message') }}
								</div>
							</div>
						@endif 
						
						<div class="row justify-content-center mt-5">
							<div class="col-md-12 p-0">
								<div class="card">
								<div class="main-panel border main-panelbg">
									<p class="p-2 pl-4 font-weight-700">{{ trans('messages.profile.current_verifications') }}</p>
								</div>

									<div class="pl-5 pr-5 p-3">
									<ul class="list-layout edit-verifications-list">
										
										@if((Auth::user()->users_verification->email == 'no') && (Auth::user()->users_verification->facebook == 'no') && (Auth::user()->users_verification->google == 'no') && (Auth::user()->users_verification->document == 'no'))
											<div class="alert alert-success mt-3" role="alert">
											{{ trans('messages.experience.no_verification_available') }}
											</div>
										@else
											@if(Auth::user()->users_verification->email == 'yes')
											<li class="edit-verifications-list-item">
												<h4 class="font-weight-700 text-16">{{ trans('messages.users_dashboard.email_address') }}</h4>
												<p class="pl-4">{{ trans('messages.profile.you_have_confirmed_email') }} <b>{{ Auth::user()->email }}</b>.  {{ trans('messages.profile.email_verified') }}
												</p>
											</li>
											@endif
											<hr>
											@if(Auth::user()->users_verification->facebook == 'yes')
											<li class="edit-verifications-list-item">
												<h4  class="font-weight-700 text-16">Facebook</h4>
												<div class="row">
												<div class="col-md-9">
													<p class="description">
													{{ trans('messages.profile.facebook_verification') }}
													</p>
												</div>
												<div class="col-md-3">
													<div class="disconnect-button-container">
														<a href="{{ url('facebookDisconnect') }}" class="btn btn-primary pl-4 pr-4 pt-3 pb-3 text-16 secondary-text-color-hover btn-block" data-method="post" rel="nofollow">{{ trans('messages.profile.disconnect') }}</a>
													</div>
												</div>
												</div>
											</li>
											@endif
											
											@if(Auth::user()->users_verification->google == 'yes')
											<li class="edit-verifications-list-item">
												<h4  class="font-weight-700 text-16">Google</h4>
												<div class="row">
												<div class="col-md-9">
													<p class="description">
													{{ trans('messages.profile.google_verification', ['site_name'=>$site_name]) }}
													</p>
												</div>
												<div class="col-md-3">
													<div class="disconnect-button-container">
													<a href="{{ url('googleDisconnect') }}" class="btn btn-warning pl-4 pr-4 pt-3 pb-3 text-16 secondary-text-color-hover  btn-block" data-method="post" rel="nofollow">{{ trans('messages.profile.disconnect') }}</a>
													</div>
												</div>
												</div>
											</li>
											@endif
											
											@if(Auth::user()->users_verification->document == 'yes')
											<li class="edit-verifications-list-item">
												<h4  class="font-weight-700 text-16">{{ trans('messages.sign_up.document') }}</h4>
												<div class="row">
												<div class="col-md-12">
													<p class="description">
													{{ trans('messages.profile.document_verification', ['site_name'=>$site_name]) }}
													</p>
												</div>
											
												</div>
											</li>
											@endif
											
										@endif
									</ul>
									</div>
								</div>
							</div>


							<div class="col-md-12 mt-4 p-0 mb-5">
							<div class="card ">
								@if(!(Auth::user()->users_verification->email == 'yes' && Auth::user()->users_verification->facebook == 'yes' && Auth::user()->users_verification->google == 'yes'))
								
								<div class="main-panel border main-panelbg">
									<p class="p-2 pl-4 font-weight-700">{{ trans('messages.profile.add_more_verifications') }}</p>
								</div>
								<div class="p-4">
									<ul>
									@if(Auth::user()->users_verification->email == 'no')
										<li>
										<h4 class="font-weight-700 text-16">
											{{ trans('messages.login.email') }}
										</h4>
										<div class="row pl-4 pt-1">
											<div class="col-md-9">
											<p>
												{{ trans('messages.profile.email_verification') }} <b>{{ Auth::user()->email }}</b>.
											</p>
											</div>

											
											<div class="col-md-3">
											<div>
												<a href="{{ url('users/new_email_confirm?redirect=verification') }}">
													<button type="button" class="btn btn-outline-success pl-4 pr-4 pt-3 pb-3 text-14 text-weight-700 w-100">{{ trans('messages.profile.connect') }}</button>
												</a>
											</div>
											</div>
										</div>
										</li>
									@endif

									@if(Auth::user()->users_verification->facebook == 'no')
										<li>
										<h4 class="font-weight-700 text-16 mt-4">
											{{ trans('messages.sign_up.facebook') }}
										</h4>
										<div class="row pl-4 pt-2">
											<div class="col-md-9">
											<p class="text-16">
												{{ trans('messages.profile.facebook_verification') }}
											</p>
											</div>
											<div class="col-md-3">
												<div>
													<a href="{{ url('facebookLoginVerification') }}">
														<button type="button" class="btn btn-outline-primary pl-4 pr-4 pt-3 pb-3 text-16 text-weight-700 w-100">{{ trans('messages.profile.connect') }}</button>
													</a>
												</div>
											</div>
										</div>
										</li>
										
										
										
									@endif
									
									@if(Auth::user()->users_verification->google == 'no')
										<li>
											<h4 class="font-weight-700 text-16 mt-4">
												{{ trans('messages.sign_up.google') }}
											</h4>
											<div class="row pl-4 pt-2">
												<div class="col-md-9">
												<p class="description text-16">
													{{ trans('messages.profile.google_verification', ['site_name'=>$site_name]) }}
												</p>
												</div>
												<div class="col-md-3">
													<div class="connect-button">
														<a href="{{URL::to('googleLoginVerification')}}">
															<button type="button" class="btn btn-outline-warning pl-4 pr-4 pt-3 pb-3 text-16 text-weight-700 w-100">{{ trans('messages.profile.connect') }}</button>
														</a>
													</div>
												</div>
											</div>
										</li>
									@endif
									
									@if(Auth::user()->users_verification->document == 'no')
										<li>
											<h4 class="font-weight-700 text-16 mt-4">
												{{ trans('messages.sign_up.doc_verification') }}
											</h4>
											<div class="row pl-4 pt-2">
												<div class="col-md-9">
												<p class="description text-16">
												    {{ trans('messages.sign_up.doc_text') }}
												</p>
												@if(Auth::user()->users_verification->reason_for_disapprove!="")
												<p class="text-danger">
												    <span class="font-weight-600 text-muted d-block"> {{ trans('messages.sign_up.disapprove_text') }} </span>
												    {{ Auth::user()->users_verification->reason_for_disapprove }}</p>
                                                @endif
												</div>
												<div class="col-md-3">
													<div class="connect-button">
														<a href="{{URL::to('documentVerification')}}">
															<button type="button" class="btn btn-outline-danger pl-4 pr-4 pt-3 pb-3 text-16 text-weight-700 w-100">												{{ trans('messages.sign_up.doc_verification') }}
															</button>
														</a>
													</div>
												</div>
											</div>
										</li>
									@endif
									
									
									
									</ul>
								</div>
								@endif
							</div>
							</div>
						</div>  
						</div>
						</div><!-- row end -->
					</div>
				</div>
			</div>
		</div>
	</div>
	@stop