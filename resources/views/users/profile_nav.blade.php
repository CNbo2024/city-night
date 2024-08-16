<nav class="navbar-expand-lg navbar-light border rounded-3 sv_profile_nav">
	<ul class="list-inline">
		<li class="">
			<a class="text-14 text-color {{ (request()->is('users/profile')) ? 'secondary-text-color font-weight-700' : '' }} text-color-hover" href="{{ url('users/profile') }}">
				{{trans('messages.sidenav.edit_profile')}}
			</a>
		</li>

		<li class="">
			<a class="text-14 text-color {{ (request()->is('users/profile/media')) ? 'secondary-text-color font-weight-700' : '' }} text-color-hover" href="{{ url('users/profile/media') }}">
				{{trans('messages.sidenav.photo')}}
			</a>
		</li>

		<li class="">
			<a class="text-14 text-color {{ (request()->is('users/edit-verification')) ? 'secondary-text-color font-weight-700' : '' }} {{ (request()->is('documentVerification')) ? 'secondary-text-color font-weight-700' : '' }} text-color-hover" href="{{ url('users/edit-verification') }}">
				{{trans('messages.sidenav.verification')}}
			</a>
		</li>

		<li class="nav-item">
			<a class="text-14 text-color {{ (request()->is('users/reviews')) ? 'secondary-text-color font-weight-700' : '' }} text-color-hover" href="{{ url('users/reviews') }}">{{trans('messages.reviews.reviews_about_you')}}</a>
		</li>
									
		<li class="nav-item">
			<a class="text-14 text-color {{ (request()->is('users/reviews_by_you')) ? 'secondary-text-color font-weight-700' : '' }} text-color-hover" href="{{ url('users/reviews_by_you') }}">{{trans('messages.reviews.reviews_by_you')}}</a>
		</li>
	</ul>
</nav>