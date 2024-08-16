@push('scripts')
<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>
@endpush
<div class="col-lg-12 p-0  d-none d-lg-block sv_usermenu"> 
	<div class="main-panel container-fluid container-fluid-90">
		<div class="">
			<ul class="list-group-flush pl-3">
				<a class="text-color" href="{{ url('hosts') }}">
					<li class="vbg-default-hover pl-20 border-0 text-15 p-2  {{ (request()->is('dashboard')) ? 'active-sidebar' : '' }}">
						{{trans('messages.header.dashboard')}}
					</li>
				</a>
				
				<a class="text-color" href="{{ url('calendar') }}">
					<li class="vbg-default-hover pl-20 border-0 text-15 p-2  {{ (request()->is('calendar')) ? 'active-sidebar' : '' }}">
						{{trans('messages.calendar')}}
					</li>
				</a>

				<a class="text-color" href="{{ url('properties') }}">
					<li class="vbg-default-hover pl-20 border-0 text-15 p-2  {{ (request()->is('properties')) ? 'active-sidebar' : '' }}">
						{{trans('messages.sidenav.my_listing')}}
					</li>
				</a>
				
				<a class="text-color" href="{{ url('my-bookings') }}">
					<li class="vbg-default-hover pl-20 border-0 text-15 p-2  {{ (request()->is('my-bookings')) ? 'active-sidebar' : '' }}">
						{{trans('messages.header.my_booking')}}
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
			</ul>
		</div>
	</div>
</div>