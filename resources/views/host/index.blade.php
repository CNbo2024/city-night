@extends('template')

@section('main')
	<div class="margin-top-85">
		<div class="row m-0">
			@include('host.menu')

		    <div class="container-fluid container-fluid-90">
	            <div class="row mt-5 svdashboard mb-5">
					<div class="col-lg-12 col-md-12">
						<div class="pt-0 pb-5 pl-5 pr-5 db-content">
						    <div class="row">
						    	@if(showIdentityDeclinedAlert()->count())
							    	<div class="col-md-12">
							    		<div class="alert alert-danger">
							    			<b>Documentos rechazados</b>

							    			@foreach (showIdentityDeclinedAlert() as $alert)
							    				<li>{{ $alert->comments }}</li>
							    			@endforeach
							    		</div>
							    	</div>
							    @endif

						    	<div class="col-md-12">
						    		<h1>
								    	{{ __('messages.we_love_seeing_you_again') . auth()->user()->first_name }}
								    </h1>

								    <h3 class="mt-5">
								    	{{ __('messages.you_reservations') }}
								    </h3>

								    <div>
								    	<button data-show="checkout" type="button" class="show-section btn btn-outline-dark text-14">
								    		{{ __('messages.checkout') }} ({{ $checkout->count() }})
								    	</button>

								    	<button data-show="progress" type="button" class="show-section btn btn-outline-dark text-14">
								    		{{ __('messages.in_progress') }} ({{ $progress->count() }})
								    	</button>

								    	<button data-show="soon" type="button" class="show-section btn btn-outline-dark text-14">
								    		{{ __('messages.it_will_arrive_soon') }} ({{ $soon->count() }})
								    	</button>

								    	<button data-show="scheduled" type="button" class="show-section btn btn-outline-dark text-14">
								    		{{ __('messages.scheduled') }} ({{ $scheduled->count() }})
								    	</button>

								    	<button data-show="unreviews" type="button" class="show-section btn btn-outline-dark text-14">
								    		{{ __('messages.review_pending') }} ({{ $checkout->count() }})
								    	</button>
								    </div>
 
								    <div class="row mt-5 result-section" id="checkout">
								    	@foreach($checkout as $item)
								    		<div class="col-md-4">
								    			<div class="card">
								    				<div class="card-body">
								    					<div class="row">
								    						<div class="col-md-10">
								    							<h1>{{ $item->user->full_name ?? 'Usuario eliminado' }}</h1>

								    							<h3>{{ $item->start_date }} al {{ $item->end_date }}</h3>
								    						</div>

								    						<div class="col-md-2">
								    							<img class="img-40x40" src="{{ $item->user->profile_src ?? '/public/images/default-profile.png' }}" alt="user">
								    						</div>
								    					</div>
								    				</div>
								    			</div>
								    		</div>
							    		@endforeach
							    	</div>

							    	<div class="row mt-5 result-section" id="progress">
								    	@foreach($progress as $item)
								    		<div class="col-md-4">
								    			<div class="card">
								    				<div class="card-body">
								    					<div class="row">
								    						<div class="col-md-10">
								    							<h1>{{ $item->user->full_name ?? 'Usuario eliminado' }}</h1>

								    							<h3>{{ $item->start_date }} al {{ $item->end_date }}</h3>
								    						</div>

								    						<div class="col-md-2">
								    							<img class="img-40x40" src="{{ $item->user->profile_src ?? '/public/images/default-profile.png' }}" alt="user">
								    						</div>
								    					</div>
								    				</div>
								    			</div>
								    		</div>
							    		@endforeach
							    	</div>

							    	<div class="row mt-5 result-section" id="soon">
								    	@foreach($soon as $item)
								    		<div class="col-md-4">
								    			<div class="card">
								    				<div class="card-body">
								    					<div class="row">
								    						<div class="col-md-10">
								    							<h1>{{ $item->user->full_name ?? 'Usuario eliminado' }}</h1>

								    							<h3>{{ $item->start_date }} al {{ $item->end_date }}</h3>
								    						</div>

								    						<div class="col-md-2">
								    							<img class="img-40x40" src="{{ $item->user ? $item->user->profile_src : '/public/images/default-profile.png' }}" alt="user">
								    						</div>
								    					</div>
								    				</div>
								    			</div>
								    		</div>
							    		@endforeach
							    	</div>

							    	<div class="row mt-5 result-section" id="scheduled">
								    	@foreach($scheduled as $item)
								    		<div class="col-md-4">
								    			<div class="card">
								    				<div class="card-body">
								    					<div class="row">
								    						<div class="col-md-10">
								    							<h1>{{ $item->user->full_name ?? 'Usuario eliminado' }}</h1>

								    							<h3>{{ $item->start_date }} al {{ $item->end_date }}</h3>
								    						</div>

								    						<div class="col-md-2">
								    							<img class="img-40x40" src="{{ $item->user->profile_src ?? '/public/images/default-profile.png' }}" alt="user">
								    						</div>
								    					</div>
								    				</div>
								    			</div>
								    		</div>
							    		@endforeach
							    	</div>

							    	<div class="row mt-5 result-section" id="unreviews">
								    	@foreach($unreviews as $item)
								    		<div class="col-md-4">
								    			<div class="card">
								    				<div class="card-body">
								    					<div class="row">
								    						<div class="col-md-10">
								    							<h1>{{ $item->user->full_name ?? 'Usuario eliminado' }}</h1>

								    							<h3>{{ $item->start_date }} al {{ $item->end_date }}</h3>
								    						</div>

								    						<div class="col-md-2">
								    							<img class="img-40x40" src="{{ $item->user->profile_src ?? '/public/images/default-profile.png' }}" alt="user">
								    						</div>
								    					</div>
								    				</div>
								    			</div>
								    		</div>
							    		@endforeach
							    	</div>
						    	</div>

						    	<div class="col-md-12 mt-5">
						    		<h1 class="mt-5">
								    	{{ __('messages.we_are_here_to_help_you') }}
								    </h1>

								    <div class="row mt-5">
								    	{{-- <div class="col-md-6">
								    		<div class="card">
								    			<div class="card-body">
								    				<div class="font-weight-bold">
								    					<i class="fa fa-users"></i> {{ __('messages.join_the_host_club_in_your_area') }}
								    				</div>

								    				<div>
								    					{{ __('messages.meet_collaborate_and_exchange_tips_with_other_hosts_and_community_members') }}
								    				</div>
								    			</div>
								    		</div>
								    	</div> --}}

								    	<div class="col-md-6">
								    		<div class="card" style="cursor: pointer" onclick="window.location.href = 'https://wa.me/59169884883?text=Hola%20CityNight%20necesito%20ayuda%20para%20publicar%20mi%20propiedad%20por%20favor'">
								    			<div class="card-body">
								    				<div class="font-weight-bold">
								    					<i class="fa fa-headphones"></i> {{ __('messages.contact_superhost_support') }}
								    				</div>

								    				<div>
								    					{{ __('messages.our_superhost_support_team_is_here_to_help_you') }}
								    				</div>
								    			</div>
								    		</div>
								    	</div>
								    </div>
						    	</div>
							</div>				
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
@stop

@push('scripts')
	<script>
		$(document).ready(function () {
			$('#unreviews').hide();
			$('#progress').hide();
			$('#soon').hide();
			$('#scheduled').hide();

			$('.show-section').click(function () {
				section = $(this).attr('data-show');
				$('.result-section').hide();
				$('#' + section).show();
			});
		});
	</script>
@endpush
