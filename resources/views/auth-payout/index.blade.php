@extends('template')

@section('main')
<div class="mb-4 margin-top-85">
	<div class="row m-0">
		@include('users.sidebar')
		<div class="col-md-12">
		    <div class="container-fluid container-fluid-90">

			<div class="main-panel">
				<div class="container-fluid">
					@if(Session::has('message'))
						<div class="row mt-3">
							<div class="col-md-12 text-13 alert {{ Session::get('alert-class') }} alert-dismissable fade in opacity-1">
								<a href="#"  class="close " data-dismiss="alert" aria-label="close">&times;</a>
								{{ Session::get('message') }}
							</div>
						</div>
					@endif 

					<div class="row">
						<div class="col-md-3 p-0 mt-5 mb-3">
							<nav class="navbar-expand-lg navbar-light border rounded-3 ">
								<ul class="sv_profile_nav">
									<li class="nav-item">
										<a class="text-14 text-color text-color-hover" href="{{ url('users/payout-list') }}">{{trans('messages.sidenav.payouts')}}</a>
									</li>

									<li class="nav-item">
										<a class="text-14 text-color text-color-hover" href="{{ url('users/payout') }}">{{trans('messages.account_sidenav.account_preference')}}</a>
									</li>

									<li class="nav-item">
										<a class="text-14 text-color {{ (request()->is('users/auth-payout')) ? 'secondary-text-color font-weight-700' : '' }} text-color-hover" href="{{ url('users/auth-payout') }}">{{trans('Setup automatic payout requests')}}</a>
									</li>
									
									<li class="nav-item">
										<a class="text-14 text-color text-color-hover" href="{{ url('users/transaction-history') }}">{{trans('messages.account_transaction.transaction')}}</a>
									</li>
									
									<li class="">
                                		<a class="text-14 text-color text-color-hover" href="{{ url('users/security') }}">
                                			{{trans('messages.account_sidenav.security')}}  
                                        </a>
                                	</li>
								

								</ul>
							</nav>
						</div>

						<div class="col-md-9 mt-5">
						    <div class="ml-3 border rounded-3 mb-5">
							<form id="change_pass" class="{{ (Auth::guard('users')->user()->password) ? 'show' : 'hide' }}" method='post' action="{{url('users/auth-payout')}}">
								{{ csrf_field() }}
								<div class="row">
									<div class="col-md-6">
	                                    <div class="form-group mt-3">
											<label for="amount">{{ trans('Set amount for automatic payment requests') }} <span class="text-danger">*</span></label>
											<input class="form-control text-16" id="amount" name="amount" type="number" min="{{ App\Models\Settings::where('name', 'min-amount-for-payout-request')->first()->value }}" required value="{{ $amount->value }}">
	                                    </div>
									</div>									
								</div>

								<div class="row">
									<div class="col-md-6">
	                                    <div class="form-group mt-3">
											<label for="amount">{{ trans('Payment method') }} <span class="text-danger">*</span></label>
											<select name="payment_method" required class="form-control">
												<option value=""></option>

												@foreach($payouts as $payout)
													<option {{ $payout->id == $amount->payment_method ? 'selected' : '' }} value="{{$payout->id}}">@if( $payout->type == 1) Paypal ({{$payout->email}}) @else Bank ({{$payout->account_number}}) @endif </option>
												@endforeach
											</select>
	                                    </div>
									</div>									
								</div>

								<div class="form-group row">
									<div class="col-md-6">
										<button type="submit" class="btn vbtn-outline-success pl-4 pr-4 pt-3 pb-3 text-16 mt-5" id="save_btn">
											<i class="spinner fa fa-spinner fa-spin d-none"></i>
											<span id="save_btn-text">{{ trans('Save changes') }}</span>
										</button>
									</div>
								</div>
							</form>
							</div>
						</div>
					</div>
				</div>
			</div></div>
		</div>
	</div>
</div>
@stop