@extends('template') 
@push('css')
<link rel="stylesheet" type="text/css" href="{{ url('public/css/daterangepicker.min.css')}}" />
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="{{URL::to('/')}}/public/css/responsive.dataTables.min.css">
@endpush
@section('main')
<div class="margin-top-85">
	<div class="row m-0">
		@include('users.sidebar')
		<div class="col-lg-12 p-0">
			<div class="container-fluid container-fluid-90">
				<div class="row">	
				    <div class="col-md-3 mt-5">
							<nav class="navbar-expand-lg navbar-light border rounded-3 ">
								<ul class="sv_profile_nav">
									<li class="nav-item">
										<a class="text-14 text-color text-color-hover" href="{{ url('users/payout-list') }}">{{trans('messages.sidenav.payouts')}}</a>
									</li>

									<li class="nav-item">
										<a class="text-14 text-color text-color-hover" href="{{ url('users/payout') }}">{{trans('messages.account_sidenav.account_preference')}}</a>
									</li>

									<li class="nav-item">
										<a class="text-14 text-color text-color-hover" href="{{ url('users/auth-payout') }}">{{trans('Setup automatic payout requests')}}</a>
									</li>
									
									<li class="nav-item">
										<a class="text-14 secondary-text-color text-color font-weight-700 text-color-hover" href="{{ url('users/transaction-history') }}">{{trans('messages.account_transaction.transaction')}}</a>
									</li>
									
									<li class="">
                                		<a class="text-14 text-color {{ (request()->is('users/security')) ? 'secondary-text-color font-weight-700' : '' }}   text-color-hover" href="{{ url('users/security') }}">
                                			{{trans('messages.account_sidenav.security')}}  
                                        </a>
                                	</li>
								
								</ul>
							</nav>
						</div>
				
				    <div class="col-md-9">
					
					<div class="row mt-4">

						<div class="col-md-12 p-0">
    						<p class="mt-3 pb-3 font-weight-600">{{ trans('messages.account_transaction.transaction') }}</p>

							<form class="form-horizontal pl-0 pr-0" enctype='multipart/form-data' action="{{ url('users/transaction-history') }}" method="GET" id='filter_form' accept-charset="UTF-8">
								{{ csrf_field() }}
								<input class="form-control" type="text" id="startDate1"  name="from" value="<?= isset($from) ? $from : '' ?>" hidden>
								<input class="form-control" type="text" id="endDate1"  name="to" value="<?= isset($to) ? $to : '' ?>" hidden>
								<div class="row justify-content-between">
									<div class="d-flex rounded-3 pt-3 pb-3  border">
										<div class="pl-3 pr-3">
											<button type="button" class="form-control pick_date pick_date-width pick-btn" id="daterange-btn2">
												<span class="float-left">
													<i class="fa fa-calendar pr-2"></i> {{ trans('messages.filter.pick_date_range') }}
												</span>
												<i class="fa fa-caret-down float-right mt-2 mr-1"></i>
											</button>
										</div>
				
										<div class="text-right pl-3 pr-3">
											<button type="submit" name="btn" class="btn vbtn-outline-success text-14 font-weight-700 pl-4 pr-4 pt-3 pb-3 mr-2">{{trans('messages.filter.filter')}}</button>
										</div>
									</div>

								</div>
							</form>
						</div>

						<div class="col-md-12 p-0">
							<div class="panel-footer">
								<div class="panel">
									<div class="panel-body">
										<div class="box mb-5">
											<div class="card-body p-0">
												<div class="table-responsive">
													{!! $dataTable->table(['class' => 'table table-striped table-hover dt-responsive pt-4 text-center', 'width' => '100%', 'cellspacing' => '0']) !!}
												</div>
												<hr class="mt-5 mb-5">
												<h3 class="">{{ trans('messages.account_transaction.penalty') }}</h3>
												<table class="table table-striped table-hover pt-4 text-center penaltytable">
													<thead>
														<tr>
															<th>{{ trans('messages.listing_description.listing_name') }}</th>
															<th>{{ trans('messages.account_transaction.bookingid') }}</th>
															<th>{{ trans('messages.account_transaction.amount') }}</th>
															<th>{{ trans('messages.filter.status') }}</th>
															<th>{{ trans('messages.account_transaction.date') }}</th>
														</tr>
													</thead>
													
													<tbody>
														<?php
															foreach($hostpenalty as $penalty)
															{
																$property_id = $penalty->property_id;
																$property 	 = App\Models\Properties::where(['id'=>$property_id])->first();
														?>
														<tr>
															<td>{{ $property->name }}</td>
															<td>{{ $penalty->booking_id }}</td>
															<td>{{ $penalty->currency_code }} {{ $penalty->amount }}</td>	
															<td>{{ $penalty->status }}</td>
															<td>{{ $penalty->updated_at }}</td>
														</tr>
															<?php } ?>
													</tbody>
												</table>
												
											</div> 
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					</div>
				</div><!-- row end-->
			</div>
		</div>
	</div>
</div>
@endsection


@push('scripts')

<script type="text/javascript" src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/js/dataTables.responsive.min.js') }}"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript" src="{{ url('public/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/daterangepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/daterangecustom.min.js') }}"></script>
<script type="text/javascript">
	$('.pagination li').addClass('page-item'); 
	$('.pagination li a').addClass('page-link');
	$('.pagination span').addClass('page-link');
</script>
<script type="text/jscript">
    $('.penaltytable').dataTable({
       
    }); 
</script>
<script type="text/javascript">
	$(function() {
		var startDate = $('#startDate1').val();
		var endDate   = $('#endDate1').val();
		dateRangeBtn1(startDate,endDate, dt=1);
		formDate (startDate, endDate);
	});
</script>
@endpush 
