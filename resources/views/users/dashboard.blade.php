@extends('template')
@section('main')
<div class="margin-top-85">
	<div class="row m-0">

		{{-- sidebar start--}}
		@include('users.sidebar')
		{{--sidebar end--}}
	    <div class="container-fluid container-fluid-90">
            <div class="row mt-5 svdashboard mb-5">
        		<div class="col-md-3">
        		    <div class="border rounded-10 p-4 mb-4">
        		    @if($result->profile_image)
                        <img width="128px" height="128" class="br-50 svprofile_pic mt-4" title="{{ Auth::user()->first_name }}" src="{{  url('public/images/profile').'/'.Auth::user()->id.'/'.$result->profile_image }}" alt="{{ $result->first_name }}">
                    @else
                        <img width="128px" height="128" class="br-50 svprofile_pic mt-4" title="{{ Auth::user()->first_name }}" src="{{  \Auth::user()->profile_src }}" alt="{{ $result->first_name }}">
                    @endif
                    <div class="text-center mt-2">
                        <a href="{{ url('users/profile/media') }}" class="underline text-14">{{trans('messages.users_dashboard.update_photo')}}</a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ url('users/delete-customer') }}/{{ Auth::user()->id }}" onclick="return confirm('Are you sure you want to delete?');" class="btn btn-danger text-14">{{trans('messages.experience.delete_account')}}</a>
                    </div>
                    
                    <div class="mb-5">
                        <i class="fa fa-shirtsinbulk text-18 mt-4 mb-4 text-muted" aria-hidden="true"></i>
                        <p class="font-weight-600 text-16">{{trans('messages.users_dashboard.identity_verification')}}</p>
                        <p class="text-14 text-muted mb-4">{{trans('messages.users_dashboard.verify_text')}}</p>
                        
                        @if($user_verification->email=='no' || $user_verification->facebook=='no' || $user_verification->google=='no' || $user_verification->document=='no')
                            <a class="p-3 svbadge rounded-4" href="{{ url('users/edit-verification') }}">{{trans('messages.users_dashboard.get_the_badge')}}</a>
                        @else
							<i class="fa fa-check-circle fa-3x text-success" aria-hidden="true"></i>
                        @endif
                    </div>
                    <hr>

                    <div class="mt-5">
                        <h4 class="font-weight-600 text-16 mb-3">{{trans('messages.users_dashboard.verified_info')}}</h4>
                        @if($user_verification->email=='yes')
                            <p class="text-14">	<i class="fa fa-check" aria-hidden="true"></i> {{trans('messages.users_dashboard.email_confirmed')}}</p>
                        @endif
                        
                        @if($user_verification->facebook=='yes')
                            <p class="text-14">	<i class="fa fa-check" aria-hidden="true"></i>	{{trans('messages.users_dashboard.facebook_confirmed')}}</p>
                        @endif
                        
                        @if($user_verification->google=='yes')
                            <p class="text-14">	<i class="fa fa-check" aria-hidden="true"></i> {{trans('messages.users_dashboard.google_confirmed')}}</p>
                        @endif
                        
                        @if($user_verification->document=='yes')
                            <p class="text-14">	<i class="fa fa-check" aria-hidden="true"></i> {{trans('messages.sign_up.document_confirmed')}}</p>
                        @endif

                    </div>
                    </div>
        		</div>
        		
		<div class="col-lg-9 col-md-9">
			<div class="pt-0 pb-5 pl-5 pr-5 db-content">
			    @if (Session::has('message'))
                    <div class="alert alert-info">{{ Session::get('message') }}</div>
                @endif
			    <div class="row ">
				    
					<div class="col-md-4">
						<div class="p-5 small-box d-flex border">
						    <div class="bg-violet svicon mr-5">
                                <i class="far fa-list-alt"></i>
                            </div>
							<div class="inner">
								<a href="{{ url('properties') }}"><h3 class="font-weight-bold m-0 text-24 ">{{ $list }}</h3></a>
								<p class="m-0 text-15 text-color"> {{trans('messages.users_dashboard.my_lists')}}</p>
							</div>
							
						</div>
					</div>

					<div class="col-md-4">
						<div class="p-5 small-box border d-flex">
						    <div class="svicon bg-green mr-5">
                                <i class="fa fa-suitcase" aria-hidden="true"></i>   
                            </div>
							<div class="inner">
								<a href="{{ url('/') }}/trips/active"><h3 class="font-weight-bold m-0 text-24">{{ $trip }}</h3></a>
								<p class="text-15 m-0 text-color">{{trans('messages.users_dashboard.my_trips')}}</p>
							</div>
						
						</div>
					</div> 

					<div class="col-md-4">
						<div class="small-box p-5 border d-flex">
						    <div class="svicon bg-orange mr-5">
								<i class="fas fa-wallet mr-2 text-16 align-middle rounded-circle p-3"></i> 
							</div>
							<div class="inner">
								<h3 class="font-weight-bold m-0 text-24"> {!! moneyFormat( $wallet->currency->symbol, $wallet->total) !!}</h3>
								<p class="text-15 m-0 text-color">{{trans('messages.users_dashboard.my_wallet')}}</p>

                            </div>
                            
						</div>
					</div>
				</div>
				
				
			    <h2 class="font-weight-700 text-color text-30">Nos encanta verte de nuevo, <span class="text-capitalize">@if($result->display_name=="") {{ $result->first_name }} @else {{ $result->display_name }} @endif</span></h2>
				<h5 class="gray-text mt-3"><strong>{{trans('messages.users_show.member_since')}} {{ $result->account_since }}</strong></h5>
                
                <div class="mt-4 mb-5">
                    <a href="{{ url('users/profile') }}" class="underline text-14 font-weight-600">{{trans('messages.header.edit_profile')}}</a>
                </div>
                
                
                
                <div class="row  ">
							<div class="col-md-12 mt-4 p-0">
									<div class="mt-0 rounded-3 pt-3 pb-3">
										<h2></i>  <span><strong>{{trans('messages.users_dashboard.about')}}</strong></span></h2>   
									</div>

					               @if(isset($details['about']))
                						<p class="text-16 m-0">{{$details['about']}}</p>
                						<br>
                					@endif
								
							</div>
						</div>
						
						<hr>
                
						<div class="row  ">
							<div class="col-md-12 mt-4 p-0">
								<div class="row">                      
									<div class="mt-0 rounded-3 pt-3 pb-3">
										<h2></i>  <span><strong>{{trans('messages.sidenav.reviews')}} ({{ $reviews_count }})</strong></span></h2>   
									</div>
									
									@if($reviews_from_guests->count() > 0 && $reviews_from_hosts->count() > 0 )
										<div class="col-md-12 p-0 mt-4">
											<ul class="nav nav-tabs" role="tablist">
												<li class="nav-item">
													<a class="nav-link active secondary-text-color text-color-hover" data-toggle="tab" href="#tabs-1" role="tab">{{trans('messages.users_show.review_guest')}}</a>
												</li>
												<li class="nav-item">
													<a class="nav-link secondary-text-color text-color-hover" data-toggle="tab" href="#tabs-2" role="tab">{{trans('messages.users_show.review_host')}}</a>
												</li>
											</ul><!-- Tab panes -->

											<div class="tab-content">
												<div class="tab-pane active" id="tabs-1" role="tabpanel">
													@foreach($reviews_from_guests as $row_host) 
														@include('users.review_list')
													@endforeach
												</div>

												<div class="tab-pane" id="tabs-2" role="tabpanel">
													@foreach($reviews_from_hosts as $row_host) 
														@include('users.review_list')
													@endforeach
												</div>
											</div>  
										</div>

									@elseif($reviews_from_guests->count() > 0)
										@foreach($reviews_from_guests as $row_host) 
											@include('users.review_list')
										@endforeach
									@elseif($reviews_from_hosts->count() > 0)
										@foreach($reviews_from_hosts as $row_host) 
											@include('users.review_list')
										@endforeach
									@endif
								</div>
							</div>
						</div>

                
			    <!--<p class="text-14">{{trans('messages.users_dashboard.welcome_txt1')}}</p>
			    <p class="text-14">{{trans('messages.users_dashboard.welcome_txt2')}}</p>
                <a class="text-14 text-danger" href="{{ url('users/profile') }}">{{trans('messages.users_dashboard.complete_your_profile')}} <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>-->
				    
				

				<!--<div class="row mb-5">
					<div class="col-lg-6 mb-4 mt-5">
						<div class="card">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-700 text-18"><i class="fa fa-bookmark  mr-1" aria-hidden="true"></i> {{trans('messages.users_dashboard.latest_bookings')}}</h6>
							</div>
							<div class="card-body">
								<div class="widget">
									<ul>
										@forelse($bookings as $booking)
										@if($loop->index < 4)
										<li>
											<div class="sidebar-thumb">
												<a href="{{ url('/') }}/properties/{{ $booking->properties->slug}}"><img class="animated rollIn" src="{{ $booking->properties->cover_photo}} " alt="coverphoto" /></a>
												
											</div>

											<div>
												<h4 class="animated bounceInRight text-16 font-weight-700">
													<a href="{{ url('/') }}/properties/{{ $booking->properties->slug}}">{{ $booking->properties->name}} 			
													</a><br/>
													
												</h4>
											</div>

											<div class="d-flex justify-content-between">
												<div>
													<div>
														<span class="text-14 font-weight-400">
															<i class="fa fa-calendar" aria-hidden="true"></i> {{ $booking->date_range}}</span>
														<div class="sidebar-meta">
															<a href="{{ url('/') }}/users/show/{{ $booking->user_id}}" class="text-14 font-weight-400">{{ $booking->users->full_name}}</a>
														</div>
													</div>
											
												</div>

												<div class="align-self-center pr-4"> 
													<span class="badge vbadge-success text-14 mt-3 p-2 {{ $booking->status}}">{{ $booking->status}}</span>
												</div>
											</div>
										</li>
										@endif
										@empty
										<div class="row jutify-content-center w-100 p-4 mt-4">
											<div class="text-center w-100">
											<p class="text-center">{{trans('messages.booking_my.no_booking')}}</p>
											</div>
										</div>
										@endforelse
									</ul>
								</div>

								@if($bookings->count()>4)
									<div class="more-btn text-right">
										<a class="btn vbtn-outline-success text-14 font-weight-700 p-0 mt-2 pl-3 pr-3" href="{{ url('/') }}/my-bookings">
											<p class="p-2 mb-0">{{trans('messages.property_single.more')}}</p>
										</a>
									</div>
								@endif
							</div>
							
							
						</div>
					</div>

					<div class="col-lg-6 mb-4 mt-5">
						<div class="card h-100">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-700 text-18"><i class="fas fa-exchange-alt mr-2"></i>{{trans('messages.users_dashboard.latest_transactions')}}</h6>
							</div>

							<div class="card-body text-16 p-0">
								<div class="panel-footer">
									<div class="panel">
										<div class="panel-body" class="p-0">
											<div class="row">
												<div class="table-responsive">
													<table class="table table-striped table-hover table-header text-center svtable">
														@if($transactions->count()>0)
															<thead>
																<tr class="">
																	<th>{{trans('messages.account_transaction.type')}}</th>
																	<th>{{trans('messages.utility.payment_method')}}</th>
																	<th>{{trans('messages.account_transaction.amount')}}</th>
																	<th>{{trans('messages.account_transaction.date')}}</th>
																</tr>
															</thead>
														@endif
															<tbody id="transaction-table-body1">
																@forelse($transactions as $transaction)
																	<tr>
																		<td>{{ is_numeric($transaction->currency_id) ? 'Payout': 'Booking'}}</td>
																		<td>{{ $transaction->payment_methods->name ?? ''}}   </td>
																		<td> 
																		  {!! Session::get('symbol') !!}
																		  {{ is_numeric($transaction->currency_id) ? currency_fix($transaction->amount, $transaction->currency->code) : currency_fix($transaction->amount, $transaction->currency_id) }} 

																		</td>
																			
																		<td>{{ onlyFormat($transaction->created_at)}}</td>
																	</tr>                              
																@empty

																<div class="row jutify-content-center w-100 p-4 mt-4">
																	<div class="text-center w-100">
																	<p class="text-center">{{trans('messages.listing_description.no')}} {{trans('messages.account_sidenav.transaction_history')}}.</p>
																	</div>
																</div>		
																@endforelse
															</tbody>
													</table>
													@if( $transactions->count() >= 9 )
														<div class="more-btn text-right mb-4 pr-4">
															<a class="btn vbtn-outline-success text-14 font-weight-700 p-0 mt-2 pl-3 pr-3" href="{{ url('/') }}/users/transaction-history">
																<p class="p-2 mb-0">{{trans('messages.property_single.more')}}</p>
															</a>
														</div>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>-->
			</div>
		</div>
	</div>
	</div>
		
		
		
	</div>
</div>
@stop    