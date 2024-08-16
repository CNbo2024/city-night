@if(auth()->user()->host)
	@include('host.menu')
@else
	@push('scripts')
	<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>
	@endpush
	<div class="col-lg-12 p-0  d-none d-lg-block sv_usermenu"> 
		<div class="main-panel container-fluid container-fluid-90">
			<div class="">
				<ul class="list-group-flush pl-3">
					<a class="text-color" href="{{ url('dashboard') }}">
						<li class="vbg-default-hover pl-20 border-0 text-15 p-2  {{ (request()->is('dashboard')) ? 'active-sidebar' : '' }}">
							{{trans('messages.header.dashboard')}}
						</li>
					</a>
					
					<a class="text-color" href="{{ url('users/profile') }}">
						<li class="vbg-default-hover pl-20  border-0 text-15 p-2 {{ (request()->is('users/profile') || request()->is('users/profile/media') || request()->is('users/edit-verification') ) ? 'active-sidebar' : '' }}">
							{{trans('messages.utility.profile')}}
						</li>
					</a>

					@if($enable_experience == "Yes")
					<a class="text-color" href="{{ url('experience') }}">
						<li class="vbg-default-hover pl-20 border-0 text-15 p-2  {{ (request()->is('experience')) ? 'active-sidebar' : '' }}">
							{{trans('messages.experience.my_experience')}}
						</li>
					</a>
					@endif
					
					<a class="text-color" href="{{ url('trips/active') }}">
						<li class="vbg-default-hover pl-20 border-0 text-15 p-2  {{ (request()->is('trips/active')) ? 'active-sidebar' : '' }}">
							{{trans('messages.header.your_trip')}}
						</li>
					</a>
					
					<a class="text-color" href="{{ url('mywishlist') }}">
						<li class="vbg-default-hover pl-20  border-0 text-15 p-2 {{ (request()->is('mywishlist')) ? 'active-sidebar' : '' }}">
							{{trans('messages.utility.wishlist')}}
						</li>
					</a>

					<a class="text-color" href="{{ url('inbox') }}">
						<li class="vbg-default-hover pl-20 border-0 text-15 p-2  {{ (request()->is('inbox')) ? 'active-sidebar' : '' }}">
							{{trans('messages.header.inbox')}}
						</li>
					</a>

					@if(App\Models\Wallet::where('user_id', auth()->user()->id)->first()->total)
						<a class="text-color" href="{{ url('users/payout-list') }}">
							<li class="vbg-default-hover pl-20  border-0 text-15 p-2 {{ (request()->is('users/payout-list' ) || request()->is('users/payout') || request()->is('users/security') ) ? 'active-sidebar' : '' }}">
								{{trans('messages.sidenav.payment_account')}}
							</li>
						</a>
					@endif

					<a class="text-color" href="{{ url('tips') }}">
						<li class="vbg-default-hover pl-20  border-0 text-15 p-2 {{ request()->is('tips') ? 'active-sidebar' : '' }}">
							{{trans('Tips')}}
						</li>
					</a>

					<a class="text-color" href="{{ url('cards') }}">
						<li class="vbg-default-hover pl-20  border-0 text-15 p-2 {{ request()->is('cards') ? 'active-sidebar' : '' }}">
							{{trans('messages.cards')}}
						</li>
					</a>

					<!--<a class="text-color" href="{{ url('users/transaction-history') }}">
						<li class="vbg-default-hover pl-20  border-0 text-15 p-2 {{ (request()->is('users/transaction-history')) ? 'active-sidebar' : '' }}">
							{{trans('messages.account_transaction.transaction')}}
						</li>
					</a>-->

					
					
				
					
	                    <!--<div class="dropdown pt-2">
							<button class="dropdown-toggle" type="button" data-toggle="dropdown">
								{{trans('messages.sidenav.reviews')}}
	    						<i class="fas fa-angle-down" id="reviewArrow"></i>
	    					</button>
											
						    <ul class="dropdown-menu">
								<li class="vbg-default-hover pl-3  border-0 text-15 pt-3 pb-3 {{ (request()->is('users/reviews')) || (request()->is('reviews/details/*')) ? 'secondary-text-color' : '' }}">
								    <a class="text-color" href="{{ url('users/reviews') }}">	{{trans('messages.reviews.reviews_about_you')}}	</a>
								</li>
						
								<li class="vbg-default-hover pl-3 border-0 text-15 pt-3 pb-3 {{ (request()->is('users/reviews_by_you')) || (request()->is('reviews/edit/*')) ? 'secondary-text-color' : '' }}">
								    <a class="text-color" href="{{ url('users/reviews_by_you') }}">	{{trans('messages.reviews.reviews_by_you')}}	</a>
								</li>
							</ul>
						</div>-->

					<!--<a class="text-color" data-toggle="collapse" href="#collapseReviews" role="button" aria-expanded="true" aria-controls="collapseReviews" id="reviewIcon">
						<li class="vbg-default-hover pl-20 border-0 text-15 p-2 {{ (request()->is('users/reviews'))  || (request()->is('users/reviews_by_you')) || (request()->is('reviews/edit/*'))  ? 'active-sidebar' : '' }}">
							
							<div class="d-flex justify-content-between">
								<div>
									<span>
										{{trans('messages.sidenav.reviews')}}
									</span>
								</div>
								<div>
									<span class="text-right pl-2">
										@if((request()->is('users/reviews')) || (request()->is('reviews/edit/*')) || (request()->is('reviews/details/*'))  || (request()->is('users/reviews_by_you')))
										<i class="fas fa-angle-down" id="reviewArrow"></i>
										@else
										<i class="fas fa-angle-right" id="reviewArrow"></i>
										@endif
									</span>
								</div>
							</div>
							
						</li>
					</a>

					<div class="collapse {{ (request()->is('users/reviews')) || (request()->is('reviews/edit/*')) || (request()->is('reviews/details/*'))  || (request()->is('users/reviews_by_you'))  ? 'show' : '' }}" id="collapseReviews">
						<ul class="pl-5">
							<a class="text-color" href="{{ url('users/reviews') }}">
								<li class="vbg-default-hover pl-5  border-0 text-15 pt-3 pb-3 {{ (request()->is('users/reviews')) || (request()->is('reviews/details/*')) ? 'secondary-text-color' : '' }}">
									{{trans('messages.reviews.reviews_about_you')}}
								</li>
							</a>

							<a class="text-color" href="{{ url('users/reviews_by_you') }}">
								<li class="vbg-default-hover pl-5 border-0 text-15 pt-3 pb-3 {{ (request()->is('users/reviews_by_you')) || (request()->is('reviews/edit/*')) ? 'secondary-text-color' : '' }}">
									{{trans('messages.reviews.reviews_by_you')}}
								</li>
							</a>
						</ul>
					</div>

					<a class="text-color" href="{{ url('logout') }}">
						<li class="vbg-default-hover pl-20 border-0 text-15 p-2">
							{{trans('messages.header.logout')}}
						</li>
					</a>-->
					
				</ul>
			</div>
		</div>
	</div>
@endif;